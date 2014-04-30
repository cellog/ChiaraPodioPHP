<?php
namespace Chiara\PodioApp\Fields;
use Chiara\PodioApp\Field, Chiara\Iterators\TopItemIterator, Chiara\AuthManager as Auth, Chiara\Remote;
class App extends Field
{
    function __construct(PodioApp $parent, array $info = array())
    {
        parent::__construct($parent, $info);
        if (!count($info)) {
            $this->info['config']['settings']['referenceable_types'] = array();
        }
    }

    function __get($var)
    {
        if ($var == 'referenceable_types') return $this->info['config']['settings']['referenceable_types'];
        return parent::__get($var);
    }

    function topValues($limit = 13, array $excludeItems = array())
    {
        Auth::prepareRemote($this->parent->id);
        return self::getTopValues($this->id, $excludeItems);
    }

    static function getTopValues($fieldid, $limit = 13, array $excludeItems = array())
    {
        $ret = Remote::$remote->get('/item/field/' . $fieldid .'/top/', array(
                                            'field_id' => $fieldid,
                                            'limit' => 13,
                                            'not_item_id' => implode(',', $excludeItems)
                                        ));
        return new TopItemIterator($ret);
    }
}