<?php
namespace Chiara\PodioItem\Fields;
class Question extends Category
{
    function getValue()
    {
        if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
            return null;
        }
        return new Option($this->info['values'][0]);
    }
}