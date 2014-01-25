<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Field;
class Collection extends \ArrayObject
{
    protected $parentObject;
    protected $wrapperClass;
    protected $map = array();
    function __construct(Field $parentObject, $objects, $wrapperClass = null)
    {
        $this->parentObject = $parentObject;
        $this->wrapperClass = $wrapperClass;
        if (!is_array($objects)) {
            $objects = array($objects);
        }
        if ($wrapperClass) {
            // wrap the values with objects so their implied information can be accessed
            $objects = array_map(function($a) use ($wrapperClass) {return new $wrapperClass($a);}, $objects);
            foreach ($objects as $i => $obj) {
                foreach ($obj->getIndices() as $index) {
                    $this->map[$index] = $i;
                }
            }
        }
        parent::__construct($objects);
    }

    function offsetGet($var)
    {
        if (isset($this->map[$var])) {
            return parent::offsetGet($this->map[$var]);
        }
        return parent::offsetGet($var);
    }

    function offsetExists($var)
    {
        return isset($this->map[$var]) || parent::offsetExists($var);
    }

    function offsetSet($index, $var)
    {
        $ret = array();
        $wrapperClass = $this->wrapperClass;
        $current = $this->offsetGet($index);
        if (is_int($var)) {
            $current->id = $var;
        } elseif (is_array($var)) {
            $this[$index] = new $wrapperClass($var);
        } elseif ($var instanceof $wrapperClass) {
            $this[$index] = $var;
        }
    }

    function saveValue()
    {
        return array_map(function($a) {return $a->saveValue();}, $this);
    }
}
