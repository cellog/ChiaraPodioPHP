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
}