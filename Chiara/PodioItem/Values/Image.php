<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem, Chiara\PodioFile;
class Image extends Reference
{
    function retrieveReference()
    {
        return new PodioFile($this->info['value']);
    }

    function extendedGet($var)
    {
        return $this->getValue()->__get($var);
    }

    function getIndices()
    {
        return array(
            $this->info['value']['file_id']
        );
    }
}
