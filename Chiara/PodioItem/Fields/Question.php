<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Values\Option;
class Question extends Category
{
    function getValue()
    {
        if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
            return null;
        }
        return new Option($this->parent, $this->info['values'][0]);
    }

    function __get($var)
    {
        if ($var == 'options') return $this->info['config']['settings']['options'];
        if ($var == 'multiple') return $this->info['config']['settings']['multiple'];
        return parent::__get($var);
    }
}