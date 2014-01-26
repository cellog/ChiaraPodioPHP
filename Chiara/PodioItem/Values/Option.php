<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class Option
{
    protected $parent;
    protected $info;
    function __construct(PodioItem $parent, $info = null)
    {
        $this->parent = $parent;
        $this->info = $info;
    }

    function getValue()
    {
        return $this->info['value']['id'];
    }

    function getIndices()
    {
        return array($this->info['value']['id']);
    }

    function __get($var)
    {
        return $this->info['value'][$var];
    }

    function __set($var, $value)
    {
        $this->info['value'][$var] = $value;
    }

    function __toString()
    {
        return $this->info['value']['text'];
    }
}
