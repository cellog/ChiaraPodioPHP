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
        if (!isset($this->info['id'])) {
            var_dump($this->info);
            throw new \Exception('test');
        }
        return $this->info['id'];
    }

    function getIndices()
    {
        return array($this->info['id']);
    }

    function __get($var)
    {
        return $this->info[$var];
    }

    function __set($var, $value)
    {
        $this->info[$var] = $value;
    }

    function __toString()
    {
        return $this->info['text'];
    }
}
