<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Field;
class Collection extends \ArrayObject
{
    protected $parentObject;
    protected $wrapperClass;
    protected $mymap = array();
    function __construct(Field $parentObject, $objects, $wrapperClass = null)
    {
        $this->parentObject = $parentObject;
        $this->wrapperClass = $wrapperClass;
        if (!is_array($objects)) {
            $objects = array($objects);
        }
        if ($wrapperClass) {
            $o = $parentObject->parentItem;
            // wrap the values with objects so their implied information can be accessed
            $objects = array_map(function($a) use ($wrapperClass, $o) {return new $wrapperClass($o, $a);}, $objects);
            foreach ($objects as $i => $obj) {
                foreach ($obj->getIndices() as $index) {
                    $this->mymap[$index] = $i;
                }
            }
        }
        parent::__construct($objects);
    }

    function __get($var)
    {
        if ($var == 'map') {
            return $this->mymap;
        }
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
        if (is_int($var)) {
            if (isset($this[$index])) {
                $current = $this->offsetGet($index);
            } else {
                $current = new $wrapperClass(array());
            }
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

    function __toString()
    {
        $x = array_reduce($this->getArrayCopy(), function(&$r, $n) {if ($r) $r .= '; '; $r .= $n; return $r;}, '');
        return $x;
    }
}
