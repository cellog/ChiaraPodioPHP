<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class ReferenceCollection extends Collection
{
    protected $appinfo = array();
    /**
     * If true, then the index passed to offsetGet is assumed to be an app_item_id reference
     */
    protected $appidretrieve = false;
    protected $realmap;
    protected $appidmap;
    function __construct(PodioItem $parent, array $appinfo, array $objects, $wrapperClass = 'Chiara\PodioItem')
    {
        $this->parentObject = $parent;
        $this->appinfo = $appinfo;
        if (!is_array($objects)) {
            $objects = array($objects);
        }
        if ($wrapperClass) {
            // wrap the values with objects so their implied information can be accessed
            $objects = array_map(function($a) use ($wrapperClass, $appinfo)
                                 {$z = new $wrapperClass($a);$z->app_id = $appinfo['app_id'];return $z;}, $objects);
            foreach ($objects as $i => $obj) {
                foreach ($obj->getIndices() as $index) {
                    $this->realmap[$index] = $i;
                }
                foreach ($obj->getIndices(true) as $index) {
                    $this->appidmap[$index] = $i;
                }
            }
        }
        unset($this->map);
        \ArrayObject::__construct($objects);
    }

    function __get($var)
    {
        if ($var == 'items') {
            $x = clone $this;
            $x->retrieveByAppId();
            return $x;
        }
        if ($var == 'map') {
            return $this->appidretrieve ? $this->appidmap : $this->realmap;
        }
    }

    function retrieveByAppId()
    {
        $this->appidretrieve = true;
    }

    function offsetSet($index, $var)
    {
        throw new \Exception('Error: cannot set a value in a reference');
    }
}