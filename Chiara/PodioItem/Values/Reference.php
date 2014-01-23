<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Field;
abstract class Reference extends Field
{
    protected $info;
    function __construct($info = null)
    {
        $this->info = $info;
    }

    abstract function retrieveReference();
    abstract function getIndices();
}
