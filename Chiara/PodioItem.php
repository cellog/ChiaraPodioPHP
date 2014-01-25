<?php
namespace Chiara;
use Podio, Chiara\Iterators\ItemFieldIterator;
class PodioItem
{
    protected $info = array();
    /**
     * The PodioApplicationStructure that defines this item's structure
     *
     * Note that fields may exist that are not in the definition (legacy deleted fields)
     * @var Chiara\PodioApplicationStructure
     */
    protected $structure;
    /**
     * @var Chiara\PodioApp
     */
    protected $app = null;

    function __construct($info = null, PodioApplicationStructure $structure = null, $retrieve = true)
    {
        $this->structure = $structure;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('item_id' => $info);
        }
        $this->info = $info;
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
            $this->info = Podio::get('/app/' . $this->info['app']['app_id'] . '/item/' .
                                     $this->info['app_item_id'])->json_body();
        } else {
            $this->info = Podio::get('/item/' . $this->info['item_id'])->json_body();
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

    function setFieldValue($index, $value)
    {
        if (!$this->structure) {
            $this->structure = PodioApplicationStructure::fromItem($this);
        }
        $this->info['fields'][$index]['values'] = $this->structure->formatValue($this->info['fields'][$index]['field_id'], $value);
    }

    function toArray()
    {
        return $this->info;
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
        if (!$this->id) {
            $result = Podio::post('/item/app/' . $this->app['app_id'], $this->toJsonArray(), $options);
            $this->id = $result['item_id'];
        } else {
            Podio::post('/item/' . $this->id . '/values', $this->toJsonArray(), $options);
        }
        return $this;
    }

    function dump()
    {
        var_export($this->info);
    }
}