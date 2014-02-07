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
        if ($var === 'file_id') return $this->info['file']['file_id'];
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