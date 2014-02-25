<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Money as Value;
class Money extends Field
{
    function getValue()
    {
        return new Value($this->parent, $this->info['values'][0]);
    }

    function getSaveValue()
    {
        return $this->getValue()->value;
    }

    function __get($var)
    {
        if ($var == 'allowed_currencies') return $this->info['config']['settings']['allowed_currencies'];
        return parent::__get($var);
    }
}