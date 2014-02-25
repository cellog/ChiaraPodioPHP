<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Value, Chiara\PodioItem;
class Money extends Value
{
    function __construct(PodioItem $parent, array $info = array())
    {
        $info['value'] = (float) $info['value'];
        parent::__construct($parent, $info);
    }

    function getValue()
    {
        return $this->info;
    }

    function __toString()
    {
        return $this->info['value'] . ' ' . $this->info['currency'];
    }
}
