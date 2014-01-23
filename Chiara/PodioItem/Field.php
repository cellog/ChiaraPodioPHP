<?php
namespace Chiara\PodioItem;
use Chiara\PodioItem;

abstract class Field
{
    protected $info;
    protected $parent;
    protected $mytype;
    function __construct(PodioItem $parent, array $info = array())
    {
        $this->info = $info;
        $this->parent = $parent;
        if (!isset($info['type']) && get_class($this) !== 'Chiara\\PodioItem\\Field') {
            $this->info['type'] = str_replace('Chiara\\PodioItem\\Fields\\', '', get_class($this));
        }
        if (isset($this->mytype)) {
            $this->info['type'] = $this->mytype;
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
        if (isset($this->info[$name])) {
            return $this->info[$name];
        }
    }

    abstract function getValue();

    function type()
    {
        return $this->info['type'];
    }

    static function newField(PodioItem $parent, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($parent, $info);
    }
}