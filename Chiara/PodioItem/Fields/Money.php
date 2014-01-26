<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Money as Value;
class Money extends Field
{
    function getValue()
    {
        return new Value($this->parent, $this->info['values'][0]);
    }
}