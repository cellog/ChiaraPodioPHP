<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Diff\Field, Chiara\PodioItem\Values\Date as Value;
class Date extends Field
{
    function getValue()
    {
        return new Value($this->info['to'][0]);
    }

    function getOldValue()
    {
        return new Value($this->info['from'][0]);
    }

    function __get($var)
    {
        if ($var === 'duration') {
            return $this->getValue()->getDuration();
        }
        return parent::__get($var);
    }
}