<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem\Field, Chiara\PodioItem;
class Embed extends Field
{
    protected $parent;
    function __construct(PodioItem $parent, $info = null)
    {
        $this->parent = $parent;
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
        if ($var == 'id' || $var == 'embed_id') {
            if (!isset($this->info['embed']) || !isset($this->info['embed']['embed_id'])) {
                return null;
            }
            return $this->info['embed']['embed_id'];
        }
        if ($var == 'url') {
            if (!isset($this->info['embed']) || !isset($this->info['embed']['url'])) {
                return null;
            }
            return $this->info['embed']['url'];
        }
        if ($var == 'file_id') {
            if (!isset($this->info['file']) || !isset($this->info['file']['file_id'])) {
                return null;
            }
            return $this->info['file']['file_id'];
        }
        if (!isset($this->info[$var])) {
            return null;
        }
        return new \ArrayObject($this->info[$var]);
    }

    function __set($var, $value)
    {
        if ($var == 'id' || $var == 'embed_id') {
            $this->info['embed']['embed_id'] = $value;
            return;
        }
        if ($var == 'url') {
            $this->info['embed']['url'] = $value;
            return;
        }
        if ($var == 'file_id') {
            $this->info['file']['file_id'] = $value;
            return;
        }
        $this->info[$var] = $value;
    }
}
