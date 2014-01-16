<?php
namespace Chiara\PodioApp;
class Field
{
    protected $info;
    function __construct(array $info = array())
    {
        $this->info = $info;
        if (!isset($info['type']) && get_class($this) !== 'Chiara\\PodioApp\\Field') {
            $this->info['type'] = str_replace('Chiara\\PodioApp\\Fields\\', '', get_class($this));
        }
    }

    function __get($name)
    {
        if ($name == 'id') {
            return $this->info['field_id'];
        }
        if (isset($this->info[$name])) {
            return $this->info[$name];
        }
    }

    function type()
    {
        return $this->info['type'];
    }

    static function newField(array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($info);
    }
}