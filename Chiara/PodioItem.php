<?php
namespace Chiara;
use Podio, Chiara\Iterators\ItemFieldIterator, Chiara\AuthManager as Auth, Chiara\Remote;
class PodioItem
{
    /**
     * override to automatically set the application ID for new items
     */
    const MYAPPID = null;
    protected $info = array();
    /**
     * The PodioApplicationStructure that defines this item's structure
     *
     * Note that fields may exist that are not in the definition (legacy deleted fields)
     * @var Chiara\PodioApplicationStructure
     */
    protected $structure = null;
    /**
     * @var Chiara\PodioApp
     */
    protected $app = null;

    function __construct($info = null, PodioApplicationStructure $structure = null, $retrieve = true)
    {
        if ($structure) {
            $this->structure = $structure;
        }
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('item_id' => $info);
        }
        $this->info = $info;
        if (!$info && static::MYAPPID) {
            $this->info = array('app' => array('app_id' => static::MYAPPID));
        }
        if (!$retrieve || !$info) return;
        $this->retrieve();
    }

    function retrieve()
    {
        if (!isset($this->info['item_id'])) {
            if (!isset($this->info['app_item_id'])) {
                // TODO: use custom exception
                throw new \Exception('Cannot retrieve item, no item_id or app_item_id');
            }
            Auth::prepareRemote($this->info['app']['app_id']);
            $this->info = Remote::$remote->get('/app/' . $this->info['app']['app_id'] . '/item/' .
                                     $this->info['app_item_id'])->json_body();
        } else {
            Auth::prepareRemote($this->info['app']['app_id']);
            $this->info = Remote::$remote->get('/item/' . $this->info['item_id'])->json_body();
        }
        $this->app = null;
    }

    function __get($var)
    {
        if ($var == 'fields') {
            return new ItemFieldIterator($this);
        }
        if ($var == 'info') {
            return $this->info;
        }
        if ($var == 'id') {
            return $this->info['item_id'];
        }
        if ($var == 'app') {
            if (!$this->app) {
                if (isset($this->info['app'])) {
                    $appinfo = $this->info['app'];
                } else {
                    $appinfo = null;
                }
                $this->app = new PodioApp($appinfo, false);
            }
            return $this->app;
        }
    }

    function __set($var, $value)
    {
        if ($var == 'id') {
            $this->info['item_id'] = $value;
        }
        $this->info[$var] = $value;
    }

    function getFieldType($field, array $info = null)
    {
        if ($this->structure) {
            $info = $this->structure->getConfig($field);
            
        }
    }

    protected function getIndex($index)
    {
        if (is_int($index) && $index < 30) {
            return $index;
        }
        foreach ($this->info['fields'] as $i => $field) {
            if ($field['external_id'] == $index) {
                return $i;
            }
            if ($field['field_id'] == $index) {
                return $i;
            }
        }
    }

    function setFieldValue($index, $value)
    {
        if (!$this->structure) {
            $this->structure = PodioApplicationStructure::fromItem($this);
        }
        $index = $this->getIndex($index);
        $this->info['fields'][$index]['values'] = $this->structure->formatValue($this->info['fields'][$index]['field_id'], $value);
    }

    function toArray()
    {
        return $this->info;
    }

    function __toString()
    {
        return $this->info['title'];
    }

    function toJsonArray()
    {
        $ret = array();
        foreach ($this->fields as $field) {
            $ret[] = array($field->external_id => $field->saveValue);
        }
    }

    function save(array $options = array())
    {
        Auth::prepareRemote($this->info['app']['app_id']);
        if (!$this->id) {
            $result = Remote::$remote->post('/item/app/' . $this->app['app_id'], $this->toJsonArray(), $options);
            $this->id = $result['item_id'];
        } else {
            Remote::$remote->post('/item/' . $this->id . '/values', $this->toJsonArray(), $options);
        }
        return $this;
    }

    function dump()
    {
        var_export($this->info);
    }
}