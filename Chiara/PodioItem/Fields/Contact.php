<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection;
class Contact extends Field
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
}