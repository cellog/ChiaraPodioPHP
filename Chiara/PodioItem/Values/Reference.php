<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Value;
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

    abstract function retrieveReference();
    abstract function getIndices();
}
