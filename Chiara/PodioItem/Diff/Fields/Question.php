<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Option;
class Question extends Category
{
    function getOldValue()
    {
        if (!isset($this->info['from']) || !isset($this->info['from'][0])) {
            return null;
        }
        return new Option($this->parent, $this->info['from'][0]['value']);
    }

    function getValue()
    {
        if (!isset($this->info['to']) || !isset($this->info['to'][0])) {
            return null;
        }
        return new Option($this->parent, $this->info['to'][0]['value']);
    }
}