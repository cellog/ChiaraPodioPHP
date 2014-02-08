<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Date as Value;
class Date extends Field
{
    function getValue()
    {
        return new Value($this->info['values'][0]);
    }

    function getSaveValue()
    {
        $ret = array();
        if (isset($this->info['start'])) {
            $ret['start'] = $this->info['start'];
        }
        if (isset($this->info['end'])) {
            $ret['end'] = $this->info['end'];
        }
        return $ret;
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