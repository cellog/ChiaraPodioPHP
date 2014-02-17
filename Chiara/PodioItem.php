<?php
namespace Chiara;
use Podio, Chiara\Iterators\ItemFieldIterator, Chiara\AuthManager as Auth, Chiara\Remote,
    Chiara\Iterators\ReferenceIterator;
class PodioItem
{
    /**
     * override to automatically set the application ID for new items
     */
    protected $MYAPPID = null;
    protected $info = array();
    protected $hasfields = false;
    protected $dirty = array();
    /**
     * The PodioApplicationStructure that defines this item's structure
     *
     * Note that fields may exist that are not in the definition (legacy deleted fields)
     * @var Chiara\PodioApplicationStructure
     */
    protected $structure = null;
    /**
     * The cached return of a call to the get references API call
     * @var array
     */
    protected $references = null;
    /**
     * @var Chiara\PodioApp
     */
    protected $app = null;

    function __construct($info = null, PodioApplicationStructure $structure = null, $retrieve = true)
    {
        if ($structure) {
            $this->structure = $structure;
        }
        $this->hasfields = false;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            if (isset($info['fields'])) {
                $this->hasfields = true; // prevent re-populating
                $this->dirty = array_keys($info['fields']);
            }
            if (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
                $this->info['app']['app_id'] = $this->MYAPPID;
            } elseif ($this->MYAPPID && $this->info['app']['app_id'] != $this->MYAPPID) {
                throw new \Exception(get_class($this) . ' item has app id set to ' . $this->info['app']['app_id'] .
                                     ', but it must be ' . $this->MYAPPID);
            }
            return;
        }
        if (is_int($info)) {
            $info = array('item_id' => $info);
        }
        $this->info = $info;
        if ($this->MYAPPID) {
            if (!$this->info) {
                $this->info = array('app' => array('app_id' => $this->MYAPPID));
            } elseif (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
                $this->info['app']['app_id'] = $this->MYAPPID;
            }
        }
        if (!$retrieve || !$info) return;
        $this->retrieve();
    }

    /**
     * This is used to allow passing an item as a hook callback.
     */
    function __invoke($post, $params)
    {
        $this->info['item_id'] = $post['item_id'];
        if (isset($post['item_revision_id'])) {
            $this->info['item_revision_id'] = $post['item_revision_id'];
        }
        if (isset($post['external_id'])) {
            $this->info['external_id'] = $post['external_id'];
        }
        $func = explode('.', $post['type']);
        $func = array_map($func, function($a){return ucfirst($a);});
        $function = 'on' . implode('', $func);
        $this->$function($params);
    }

    function createHook($podioaction, $action = null)
    {
        return HookServer::$hookserver->makeHook($this->app, $action, $podioaction);
    }

    /**
     * override these functions in a child class to allow fine-grained handling of hooks
     */
    function onItemCreate($params) {}
    function onItemUpdate($params) {}
    function onItemDelete($params) {}
    function onCommentCreate($params) {}
    function onCommentDelete($params) {}
    function onFileChange($params) {}

    function retrieve($force = false)
    {
        if ($this->hasfields && !$force) {
            return;
        }
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
        $this->hasfields = true;
        return $this;
    }

    function getIndices($use_app_item_id = false)
    {
        if ($use_app_item_id) {
            return array(
                $this->info['app_item_id'],
                $this->info['link']
            );
        }
        return array(
            $this->info['item_id'],
            $this->info['link']
        );
    }

    function updateReferences(array $references = null)
    {
        if ($references) {
            $this->references = $references;
        } else {
            $this->references = Remote::$remote->get('/item/' . $this->info['item_id'] . '/reference')->json_body();
        }
    }

    function __get($var)
    {
        if ($var == 'fields') {
            $this->retrieve();
            return new ItemFieldIterator($this);
        }
        if ($var == 'info') {
            return $this->info;
        }
        if ($var == 'id') {
            return $this->info['item_id'];
        }
        if ($var == 'references') {
            if (!isset($this->references)) {
                $this->updateReferences();
            }
            return new ReferenceIterator($this, $this->references);
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
            $this->hasfields = true;
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
            if ($field->type == 'calculation') {
                continue;
            }
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
        if (!isset($this->info['fields'])) {
            $this->hasfields = false;
        }
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

    function generateClass($classname, $appid, $structureclass, $namespace = null, array $implements = array(), $filename = null)
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
        $ret .= "    protected \$MYAPPID=" . $appid . ";\n";
        $ret .= '    function __construct($info = null, $retrieve = true)' . "\n";
        $ret .= "    {\n";
        $ret .= "        parent::__construct(\$info, new \\$structureclass, \$retrieve);\n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    /**\n";
        $ret .= "     * handle an item.create hook in here\n";
        $ret .= "     * @param array any url-specific parameters passed in to\n";
        $ret .= "     *              differentiate between hooks.  The item is already set up\n";
        $ret .= "     *              and can be used immediately.\n";
        $ret .= "     */\n";
        $ret .= "    function onItemCreate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onItemUpdate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onItemDelete(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onCommentCreate(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onCommentDelete(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "\n";
        $ret .= "    function onFileChange(\$params)\n";
        $ret .= "    {\n";
        $ret .= "        \n";
        $ret .= "    }\n";
        $ret .= "}\n";
        if ($filename) {
            file_put_contents($filename, $ret);
        }
        return $ret;
    }
}