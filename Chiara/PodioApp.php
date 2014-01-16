<?php
namespace Chiara;
use Podio;
class PodioApp
{
    protected $appid;
    protected $info;
    function __construct($appid = null, $retrieve = true)
    {
        if (is_array($appid)) {
            $this->info = $appid;
            $this->appid = $this->info['app_id'];
            return;
        }
        $this->appid = $appid;
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        $this->info = Podio::get('/app/' . $this->appid)->json_body();
    }

    function __get($var)
    {
        if ($var === 'info') return $this->info;
        if ($var === 'fields') return new Iterators\AppFieldIterator($this);
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function dump()
    {
        var_export($this->info);
    }
}