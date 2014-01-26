<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Value;
class Money extends Value
{
    function __toString()
    {
        return $this->info['value'] . ' ' . $this->info['currency'];
    }
}
