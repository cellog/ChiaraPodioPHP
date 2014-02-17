<?php
namespace Chiara\PodioApp;
use Chiara\Hook;
class Field
{
    protected $info;
    protected $hookmanager = null;
    function __construct(array $info = array())
    {
        $this->info = $info;
        if (!isset($info['type']) && get_class($this) !== 'Chiara\\PodioApp\\Field') {
            $this->info['type'] = str_replace('Chiara\\PodioApp\\Fields\\', '', get_class($this));
        }
    }

    function __get($var)
    {
        if ($var === 'id') {
            return $this->info['field_id'];
        }
        if ($var === 'on') return $this->hookmanager ? $this->hookmanager : $this->hookmanager = new Hook\Manager($this);
        if (isset($this->info[$var])) {
            return $this->info[$var];
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