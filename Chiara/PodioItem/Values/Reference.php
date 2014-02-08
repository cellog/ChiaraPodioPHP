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
        if ($this->info == null) {
            return $this->getValue()->__get($var);
        } else {
            if ($this->extendedGet($var)) {
                return $this->extendedGet($var);
            }
            if (!isset($this->info[$var])) {
                return null;
            }
            return $this->info[$var];
        }
    }

    function extendedGet($var)
    {
        return null;
    }

    abstract function retrieveReference();
    abstract function getIndices();
}
