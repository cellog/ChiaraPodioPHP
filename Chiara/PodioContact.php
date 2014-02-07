<?php
namespace Chiara;
use Podio, Chiara\AuthManager as Auth, Chiara\Remote;
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
        Auth::verifyNonApp('contacts');
        $this->info = Remote::$remote->get('/contact/' . $this->info['profile_id'] . '/v2')->json_body;
        $this->is_space = $this->info['type'] == 'space';
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['profile_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function __set($var, $value)
    {
        if ($var === 'id') $var = 'profile_id';
        $this->info[$var] = $value;
    }

    function __toString()
    {
        return $this->info['name'];
    }
}