<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Diff\Field, Chiara\PodioItem\Values\Collection, Chiara\PodioItem\Values\Option;
class Category extends Field
{
    protected function getActualValue($field)
    {
        if (!$this->info['config']['settings']['multiple']) {
            if (!isset($this->info[$field]) || !isset($this->info[$field][0])) {
                return null;
            }
            return new Option($this->parent, $this->info[$field][0]['value']);
        }
        if (!isset($this->info[$field]) || !isset($this->info[$field][0])) {
            return new Collection($this, $this->info[$field], 'Chiara\\PodioItem\\Values\\Option');
        }
        return new Collection($this, $this->info[$field], 'Chiara\\PodioItem\\Values\\Option');
    }

    function getOldValue()
    {
        return $this->getActualValue('from');
    }

    function getValue()
    {
        return $this->getActualValue('to');
    }

    function __get($var)
    {
        if ($var == 'options') return $this->info['config']['settings']['options'];
        if ($var == 'multiple') return $this->info['config']['settings']['multiple'];
        return parent::__get($var);
    }
}