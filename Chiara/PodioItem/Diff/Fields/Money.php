<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Diff\Field, Chiara\PodioItem\Values\Money as Value;
class Money extends Field
{
    function getOldValue()
    {
        return new Value($this->parent, $this->info['from'][0]);
    }

    function getValue()
    {
        return new Value($this->parent, $this->info['to'][0]);
    }
}