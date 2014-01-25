<?php
namespace Chiara;
use Podio;
class PodioContact
{
    protected $info;
    protected $is_space = false;

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
        $this->info = Podio::get('/contact/' . $this->info['profile_id'] . '/v2')->json_body;
        $this->is_space = $this->info['type'] == 'space';
    }

    function __get($var)
    {
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function __set($var, $value)
    {
        $this->info[$var] = $value;
    }
}