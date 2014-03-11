<?php
namespace Chiara;
use Podio, Chiara\Iterators\ItemFieldIterator, Chiara\AuthManager as Auth, Chiara\Remote,
    Chiara\Iterators\ReferenceIterator, Chiara\Iterators\ItemRevisionDiffIterator as DiffIterator,
    Chiara\PodioItem\Field;
class PodioItem
{
    /**
     * override to automatically set the application ID for new items
     */
    protected $MYAPPID = null;
    protected $info = array();
    protected $hasfields = false;
    /**
     * If true, then {@link self::retrieve()} will use the "external_id" field
     * instead of "item_id" or "app_item_id"
     * @var bool
     */
    protected $useExternalIds = false;
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
    protected $myapp = null;

    function __construct($info = null, PodioApplicationStructure $structure = null, $retrieve = true, $externalid = false)
    {
        if ($structure) {
            $this->structure = $structure;
        }
        $this->hasfields = false;
        $this->useExternalIds = $externalid;
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
     * This differs in that it will instantiate the correct class for the application id
     * @return Chiara\PodioItem
     */
    static function factory($info = null, PodioApplicationStructure $structure = null, $retrieve = true)
    {
        if (is_int($info)) {
            $appid = 0;
        } elseif (is_array($info)) {
            if (!isset($info['app']) || !isset($info['app']['app_id'])) {
                $appid = 0;
            } else {
                $appid = $info['app']['app_id'];
            }
        } else {
            return new self($info, $structure, $retrieve);
        }
        $class = Auth::getTokenManager()->getAppClass($appid, __CLASS__);
        return new $class($info, $structure, $retrieve = false);
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

    function useExternalIds()
    {
        $this->useExternalIds = true;
    }

    // TODO: document this
    function retrieveValuesOnly()
    {
        return $this->retrieve(true, '/value');
    }

    function retrieve($force = false, $basic = false)
    {
        if ($this->hasfields && !$force) {
            return;
        }
        if (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
            // TODO: use custom exception
            throw new \Exception('Cannot authenticate item, no app_id');
        }
        if ($this->useExternalIds) {
            if (!isset($this->info['app_item_id'])) {
                // TODO: use custom exception
                throw new \Exception('Cannot retrieve item, no item_id or app_item_id');
            }
            if (!isset($this->info['external_id'])) {
                // TODO: use custom exception
                throw new \Exception('Cannot retrieve item, no external_id');
            }
            Auth::prepareRemote($this->info['app']['app_id']);
            $this->info = Remote::$remote->get('/item/app/' . $this->info['app']['app_id'] . '/external_id/' .
                                     $this->info['external_id'])->json_body();
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
            if ($basic) {
                if ($basic != '/value') {
                    $basic = '/basic';
                    $this->info['fields'] = Remote::$remote->get('/item/' . $this->info['item_id'] . $basic)->json_body();
                    $this->dirty = array();
                    $this->hasfields = true;
                    return $this;
                }
            } else {
                $basic = '';
            }
            $this->info = Remote::$remote->get('/item/' . $this->info['item_id'] . $basic)->json_body();
        }
        $this->myapp = null;
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
            if (!$this->myapp) {
                if (isset($this->info['app'])) {
                    $appinfo = $this->info['app'];
                } else {
                    $appinfo = null;
                }
                $this->myapp = new PodioApp($appinfo, false);
            }
            return $this->myapp;
        }
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
        if ($var == 'meetingurl') {
            if (!isset($this->info['app']) || !isset($this->info['app']['app_id'])) {
                // TODO: use custom exception
                throw new \Exception('Cannot authenticate item, no app_id');
            }
            if (!isset($this->info['item_id'])) {
                throw new \Exception('Cannot retrieve meeting url, no item_id');
            }
            Auth::prepareRemote($this->info['app']['app_id']);
            $url = Remote::$remote->get('/item/' . $this->id . '/meeting/url');
            return $url['url'];
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

    function diff($revision)
    {
        if (!is_array($this->info)) {
            $this->retrieve();
        }
        if (!isset($this->info['current_revision'])) {
            throw new \Exception('Cannot retrieve diff, current revision is unknown');
        }
        return new DiffIterator($this, $this->getRevisionDiff($this->info['current_revision']['revision'], $revision));
    }

    function getRevisionDiff($r1, $r2)
    {
        return Remote::$remote->get('/item/' . $this->id . '/revision/' . $r1 . '/' . $r2)->json_body();
    }

    function revert($revision_id)
    {
        if (!is_int($revision_id) || $revision_id < 0) {
            throw new \Exception('Revision must be a positive integer');
        }
        $response = Remote::$remote->delete('/item/' . $this->id . '/revision/' . $revision_id);
        return new Revision($this, $response['revision'], false);
    }

    function getFieldName($fieldid)
    {
        if ($this->structure) {
            return $this->structure->getName($fieldid);
        }
        return null;
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
        $options = Auth::getOptions($options);
        if (!$this->id) {
            $result = Remote::$remote->post('/item/app/' . $this->app['app_id'], $jsonarray, $options);
            $this->id = $result['item_id'];
        } else {
            Remote::$remote->post('/item/' . $this->id . '/values', $jsonarray, $options);
        }
        $this->dirty = array();
        return $this;
    }

    function delete(array $options = array())
    {
        if (!$this->id) {
            throw new \Exception("Cannot delete item, no item_id is set");
        }
        if (!$this->info['app']['app_id']) {
            throw new \Exception("Cannot delete item, no app_id is set");
        }
        Auth::prepareRemote($this->info['app']['app_id']);
        $options = Auth::getOptions($options);
        Remote::$remote->delete('/item/' . $this->id);
    }

    function __clone()
    {
        if (!$this->id) {
            throw new \Exception("Cannot clone item, no item_id is set");
        }
        if (!$this->info['app']['app_id']) {
            throw new \Exception("Cannot clone item, no app_id is set");
        }
        Auth::prepareRemote($this->info['app']['app_id']);
        $options = Auth::getOptions($options);
        $info = Remote::$remote->post('/item/' . $this->info['item_id'] . '/clone', '', $options);
        $this->info['item_id'] = $info['item_id'];
    }

    function saveField(Field $field, array $options = array())
    {
        if (!$this->id) {
            throw new \Exception('Cannot update individual field values unless the item already exists');
        }
        Auth::prepareRemote($this->info['app']['app_id']);
        $options = Auth::getOptions($options);
        
        Remote::$remote->post('/item/' . $this->id . '/value/' . $field->id,
                              array($field->external_id => $field->saveValue), $options);
        $i = $this->getIndex($field->external_id);
        if (isset($this->dirty[$i])) {
            unset($this->dirty[$i]);
        }
    }

    function dump()
    {
        var_export($this->info);
    }

    function generateClass($classname, $appid, $structureclass, $namespace = null, array $implements = array(), $filename = null)
    {
        return $this->app->generateClass($classname, $appid, $structureclass, $namespace, $implements, $filename, $this);
    }
}