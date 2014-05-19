<?php
namespace Chiara\PodioItem;
use Chiara\PodioItem;

abstract class Field extends \IteratorIterator
{
    protected $info;
    protected $parent;
    protected $mytype;
    function __construct(PodioItem $parent, array $info = array(), $iteration = false)
    {
        $this->info = $info;
        $this->parent = $parent;
        if (!isset($info['type'])) {
            $type = explode('\\', get_class($this));
            $type = strtolower(array_pop($type));
            $this->info['type'] = $type;
        }
        if (isset($this->mytype)) {
            $this->info['type'] = $this->mytype;
        }
        if ($iteration) {
            parent::__construct($this->getValue());
        }
    }

    function __get($name)
    {
        if ($name == 'id') {
            return $this->info['field_id'];
        }
        if ($name == 'value') {
            return $this->getValue();
        }
        if ($name == 'saveValue') {
            return $this->getSaveValue();
        }
        if ($name == 'parentItem') {
            return $this->parent;
        }
        if (isset($this->info[$name])) {
            return $this->info[$name];
        }
    }

    function getValue()
    {
        if (!isset($this->info['values'][0])) {
            return null;
        }
        return $this->info['values'][0]['value'];
    }

    function getSaveValue()
    {
        return $this->getValue();
    }

    function __toString()
    {
        return (string) $this->getValue();
    }

    function type()
    {
        return $this->info['type'];
    }

    function saveValue()
    {
        return $this->info['values'][0]['value'];
    }

    function saveFieldValue(array $options = array())
    {
        $this->parent->saveField($this, $options);
    }

    function save(array $options = array())
    {
        return $this->saveFieldValue($options);
    }

    static function newField(PodioItem $parent, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($parent, $info);
    }
}