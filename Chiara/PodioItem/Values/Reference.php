<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Value, Chiara\PodioItem;
abstract class Reference extends Value
{
    protected $info;
    function __construct(PodioItem $parent, $info = null)
    {
        $this->parent = $parent;
        $this->info = $info;
    }

    function getValue()
    {
        return $this->retrieveReference();
    }

    function __get($var)
    {
        return $this->getValue()->__get($var);
    }

    abstract function retrieveReference();
    abstract function getIndices();
}
