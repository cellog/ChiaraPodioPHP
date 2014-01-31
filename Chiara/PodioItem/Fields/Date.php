<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Date as Value;
class Date extends Field
{
    function getValue()
    {
        return new Value($this->info['values'][0]);
    }

    function __get($var)
    {
        if ($var === 'duration') {
            return $this->getValue()->getDuration();
        }
        return parent::__get($var);
    }

    function __set($var, $value)
    {
        if ($var === 'duration') {
            
        }
        return parent::__set($var, $value);
    }
}