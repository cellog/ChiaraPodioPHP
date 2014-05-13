<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\IteratableField, Chiara\PodioItem\Values\Collection;
class Contact extends IteratableField
{
    function getValue()
    {
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Contact');
    }

    function __get($var)
    {
        if ($var == 'contact_type') return $this->info['config']['settings']['type'];
        return parent::__get($var);
    }

    function getSaveValue()
    {
        $value = $this->getValue();
        $ret = array();
        foreach ($value as $v) {
            $ret[] = $v->id;
        }
        return $ret;
    }
}