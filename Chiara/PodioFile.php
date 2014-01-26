<?php
namespace Chiara;
use Podio;
class PodioFile
{
    protected $info;

    function __construct($info = null, $retrieve = true)
    {
        $this->info = $info;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('profile_id' => $info);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        $this->info = Podio::get('/file/' . $this->info['file_id'])->json_body;
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['file_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function __set($var, $value)
    {
        $this->info[$var] = $value;
    }
}