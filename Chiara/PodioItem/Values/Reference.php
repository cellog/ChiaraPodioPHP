<?php
namespace Chiara\PodioItem\Values;
abstract class Reference
{
    protected $info;
    function __construct($info = null)
    {
        $this->info = $info;
    }

    abstract function retrieveReference();
    abstract function getIndices();
}
