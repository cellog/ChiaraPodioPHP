<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class Image extends Reference
{
    function retrieveReference()
    {
        return new PodioFile($this->info['value']['file_id']);
    }

    function getIndices()
    {
        return array(
            $this->info['value']['file_id']
        );
    }

    function saveValue()
    {
        return $this->info['value']['file_id'];
    }
}
