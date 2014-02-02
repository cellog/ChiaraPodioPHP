<?php
namespace Chiara;
use Podio, Chiara\AuthManager as Auth;
class PodioApp
{
    protected $info;
    function __construct($appid = null, $retrieve = true)
    {
        if (is_array($appid)) {
            $this->info = $appid;
            if ($retrieve !== 'force') return;
        } else {
            $this->info = array('app_id' => $appid);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::prepareRemote($this->id);
        $this->info = Remote::$remote->get('/app/' . $this->id)->json_body();
    }

    function __get($var)
    {
        if ($var === 'info') return $this->info;
        if ($var === 'fields') return new Iterators\AppFieldIterator($this);
        if ($var === 'id') return $this->info['app_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function dump()
    {
        var_export($this->info);
    }
}