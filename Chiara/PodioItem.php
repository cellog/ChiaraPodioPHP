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
    protected $dirty = array();
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
            if (isset($info['fields'])) {
                $this->dirty = array_keys($info['fields']);
            }
            if (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
                $this->info['app']['app_id'] = static::MYAPPID;
            } elseif (static::MYAPPID && $this->info['app']['app_id'] != static::MYAPPID) {
                throw new \Exception(get_class($this) . ' item has app id set to ' . $this->info['app']['app_id'] .
                                     ', but it must be ' . static::MYAPPID);
            }
            return;
        }
        if (is_int($info)) {
            $info = array('item_id' => $info);
        }
        $this->info = $info;
        if (static::MYAPPID) {
            if (!$this->info) {
                $this->info = array('app' => array('app_id' => static::MYAPPID));
            } elseif (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
                $this->info['app']['app_id'] = static::MYAPPID;
            }
        }
        if (!$retrieve || !$info) return;
        $this->retrieve();
    }

    function retrieve()
    {
        if (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
            // TODO: use custom exception
            throw new \Exception('Cannot authenticate item, no app_id');
        }
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
        $this->dirty = array();
        return $this;
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
        if ($var === 'structure') {
            return $this->structure;
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
        if ($var == 'app_id') {
            $this->info['app']['app_id'] = $value;
        }
        $this->info[$var] = $value;
        if ($var == 'fields') {
            $this->dirty = array_keys($this->info['fields']);
        }
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
        $newvalue = $this->structure->formatValue($this->info['fields'][$index]['field_id'], $value);
        if ($newvalue == $this->info['fields'][$index]['values']) {
            return;
        } else {
            $this->dirty[$index] = true;
        }
        $this->info['fields'][$index]['values'] = $newvalue;
    }

    function toArray()
    {
        return $this->info;
    }

    function __toString()
    {
        return $this->info['title'];
    }

    /**
     * Convert to an array that can be used to save the item
     *
     * @param bool $force if true, all fields will be saved, regardless of modification state,
     *                    otherwise, only fields that have been modified will be saved
     */
    function toJsonArray($force = false)
    {
        $ret = array();
        if ($force) {
            $array = $this->fields;
        } else {
            $array = $this->dirty;
        }
        foreach ($array as $i => $field) {
            if (!$force) $field = $this->fields[$this->info['fields'][$i]['field_id']];
            $ret[$field->external_id] = $field->saveValue;
        }
        return $ret;
    }

    /**
     * Mark a podio item as unmodified
     */
    function clean()
    {
        $this->dirty = array();
    }

    function save(array $options = array())
    {
        $jsonarray = $this->toJsonArray();
        
        if (!count($jsonarray)) {
            // no changes, no need to pollute the internets
            return;
        }
        Auth::prepareRemote($this->info['app']['app_id']);
        if (!$this->id) {
            $result = Remote::$remote->post('/item/app/' . $this->app['app_id'], $jsonarray, $options);
            $this->id = $result['item_id'];
        } else {
            Remote::$remote->post('/item/' . $this->id . '/values', $jsonarray, $options);
        }
        $this->dirty = array();
        return $this;
    }

    function dump()
    {
        var_export($this->info);
    }

    function generateClass($classname, $structureclass, $namespace = null, array $implements = array(), $filename = null)
    {
        $ret = "<?php\n";
        if ($namespace) {
            $ret .= "namespace $namespace;\n";
        }
        if ($implements) {
            $implements = ' implements ' . implode(', ', $implements);
        } else {
            $implements = '';
        }
        $ret .= "class $classname$implements extends \\" . get_class($this) . "\n";
        $ret .= "{\n";
        $ret .= '    function __construct($info = null, $retrieve = true)' . "\n";
        $ret .= "    {\n";
        $ret .= "        parent::__construct(\$info, new \\$structureclass, \$retrieve);\n";
        $ret .= "    }\n";
        $ret .= "}\n";
        if ($filename) {
            file_put_contents($filename, $ret);
        }
        return $ret;
    }
}