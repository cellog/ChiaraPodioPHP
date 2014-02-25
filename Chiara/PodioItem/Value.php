<?php
namespace Chiara\PodioItem;
use Chiara\PodioItem;

abstract class Value extends Field
{
    function __construct(PodioItem $parent, array $info = array())
    {
        parent::__construct($parent, $info);
        $this->info = $info; // ensure info['type'] is not set
    }

    function __set($name, $value)
    {
        $this->info[$name] = $value;
    }

    function getValue()
    {
        return $this->info['value'];
    }
}