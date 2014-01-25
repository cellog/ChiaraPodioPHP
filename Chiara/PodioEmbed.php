<?php
namespace Chiara;
use Podio;
class PodioEmbed
{
    protected $info;
    function __construct($embedid = null)
    {
        if (is_array($embedid)) {
            $this->info = $embedid;
            $this->embedid = $this->info['embed']['embed_id'];
            return;
        }
        $this->embedid = $embedid;
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['embed']['embed_id'];
        if ($var === 'url') return $this->info['embed']['url'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function __set($var, $value)
    {
        if ($var === 'url') {
            $this->info['embed']['url'] = $value;
        }
        if ($var === 'id') {
            $this->info['embed']['embed_id'] = $value;
        }
    }

    function dump()
    {
        var_export($this->info);
    }
}