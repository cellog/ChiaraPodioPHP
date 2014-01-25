<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class Embed extends Reference
{
    function retrieveReference()
    {
        return new PodioEmbed($this->info, null, 'force');
    }

    function getIndices()
    {
        return array(
            $this->info['embed']['embed_id']
        );
    }

    function __get($var)
    {
        // do this next
    }

    function __set($var, $value)
    {
        
    }
}
