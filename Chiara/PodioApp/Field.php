<?php
namespace Chiara\PodioApp;
use Chiara\Hook, Chiara\PodioApp, Chiara\AuthManager as Auth,
    Chiara\Remote;
class Field
{
    protected $info;
    protected $parent;
    protected $hookmanager = null;
    protected $canDoRange = false;
    protected $range = false;
    function __construct(PodioApp $parent, array $info = array())
    {
        $this->parent = $parent;
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
        if ($var === 'on' || $var === 'hook') return $this->hookmanager ? $this->hookmanager : $this->hookmanager = new Hook\Manager($this);
        if ($var === 'range') {
            if ($this->range) {
                return $this->range;
            }
            if (!$this->canDoRange) {
                throw new \Exception('Error: only Number, Calculation and Money fields can retrieve range information');
            }
            Auth::prepareRemote($this->parent->id);
            $this->range = Remote::$remote->get('/item/field/' . $this->info['field_id'] . '/range')->json_body();
            return $this->range;
        }
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function type()
    {
        return $this->info['type'];
    }

    static function newField(PodioApp $parent, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($parent, $info);
    }
}