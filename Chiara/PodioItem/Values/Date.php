<?php
namespace Chiara\PodioItem\Values;
class Date
{
    protected $info;
    function __construct($info = null)
    {
        $this->info = $info;
    }

    function getValue()
    {
        return $this->info;
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
