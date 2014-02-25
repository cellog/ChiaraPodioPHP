<?php
namespace Chiara\PodioItem\Diff;
use Chiara\PodioItem, Chiara\PodioItem\Field as PodioField;

abstract class Field extends PodioField
{
    protected $info;
    protected $parent;
    protected $mytype;
    function __construct(PodioItem $parent, array $info = array())
    {
        $this->info = $info;
        $this->parent = $parent;
        if (!isset($info['type'])) {
            $this->info['type'] = str_replace('Chiara\\PodioItem\\Fields\\Diff\\', '', get_class($this));
        }
        if (isset($this->mytype)) {
            $this->info['type'] = $this->mytype;
        }
    }

    function __get($name)
    {
        if ($name == 'to') {
            return $this->getValue();
        }
        if ($name == 'from') {
            return $this->getOldValue();
        }
        return parent::__get($name);
    }

    function getOldValue()
    {
        return $this->info['from'][0]['value'];
    }

    function getValue()
    {
        return $this->info['to'][0]['value'];
    }

    function getSaveValue()
    {
        throw new \Exception('saving is not allowed from a revision diff');
    }

    function __toString()
    {
        return $this->getOldValue() . ' => ' . $this->getValue();
    }

    function type()
    {
        return $this->info['type'];
    }

    function saveValue()
    {
        throw new \Exception('saving is not allowed from a revision diff');
    }

    static function newField(PodioItem $parent, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($parent, $info);
    }
}