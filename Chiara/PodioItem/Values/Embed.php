<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Field;
class Embed extends Field
{
    function __construct($info = null)
    {
        $this->info = $info;
    }

    function getValue()
    {
        return new PodioEmbed($this->info);
    }

    function getIndices()
    {
        return array(
            $this->info['embed']['embed_id']
        );
    }

    function __get($var)
    {
        return new \ArrayObject($this->info[$var]);
    }

    function __set($var, $value)
    {
        if ($var == 'id' || $var == 'embed_id') {
            $this->info['embed']['embed_id'] = $value;
            return;
        }
        if ($var == 'file_id') {
            $this->info['file']['file_id'] = $value;
            return;
        }
        $this->info[$var] = $value;
    }
}
