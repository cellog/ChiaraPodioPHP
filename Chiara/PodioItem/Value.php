<?php
namespace Chiara\PodioItem;
use Chiara\PodioItem;

abstract class Value extends Field
{
    function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    function getValue()
    {
        return $this->info['value'];
    }
}