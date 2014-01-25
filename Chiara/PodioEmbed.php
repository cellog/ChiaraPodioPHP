<?php
namespace Chiara;
use Podio;
class PodioEmbed
{
    protected $embedid;
    protected $info;
    function __construct($embedid = null)
    {
        if (is_array($embedid)) {
            $this->info = $embedid;
            $this->embedid = $this->info['embed_id'];
            return;
        }
        $this->embedid = $embedid;
    }

    function retrieve()
    {
        // does nothing
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['embed_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function dump()
    {
        var_export($this->info);
    }
}