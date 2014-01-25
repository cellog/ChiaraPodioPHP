<?php
namespace Chiara\PodioItem\Values;
class Option
{
    protected $info;
    function __construct($info = null)
    {
        $this->info = $info;
    }

    function getValue()
    {
        return $this->info['id'];
    }

    function __get($var)
    {
        return $this->info[$var];
    }

    function __set($var, $value)
    {
        $this->info[$var] = $value;
    }
}
