<?php
namespace Chiara;
class PodioView
{
    protected $info;
    protected $appid;
    function __construct($appid, $viewid = null, $retrieve = true)
    {
        $this->appid = $appid;
        if (is_array($viewid)) {
            $this->info = $viewid;
            if ($retrieve !== 'force') return;
        } else {
            $this->info = array('view_id' => $viewid);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::prepareRemote($this->appid);
        $this->info = Remote::$remote->get('/view/app/' . $this->appid . '/' . $this->id)->json_body();
    }

    function __get($var) {
        if ($var == 'id') {
            return $this->info['view_id'];
        }
        return $this->info[$var];
    }
}