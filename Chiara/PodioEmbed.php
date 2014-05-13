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
            $this->id = $this->info['embed']['embed_id'];
            return;
        }
        $this->info = array('embed' => array());
        if (is_string($embedid)) {
            $this->url = $embedid;
        } else if (is_int($embedid)) {
            $this->id = $embedid;
        }
    }

    function __get($var)
    {
        if ($var === 'id' && isset($this->info['embed'])) return $this->info['embed']['embed_id'];
        if ($var === 'file_id' && isset($this->info['file'])) return $this->info['file']['file_id'];
        if ($var === 'url' && isset($this->info['embed']) && isset($this->info['embed']['url'])) return $this->info['embed']['url'];
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
        if ($var === 'file_id') {
            $this->info['file']['file_id'] = $value;
        }
        $this->info[$var] = $value;
    }

    function toArray()
    {
        $ret = array('embed' => array(), 'file' => array());
        if ($this->id) {
            $ret['embed']['embed_id'] = $this->id;
        }
        if ($this->file_id) {
            $ret['file']['file_id'] = $this->file_id;
        }
        if ($this->url) {
            $ret['embed']['url'] = $this->url;
        }
        return $ret;
    }

    function dump()
    {
        var_export($this->info);
    }
}