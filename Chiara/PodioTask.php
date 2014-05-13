<?php
namespace Chiara;
use Chiara\AuthManager as Auth, Chiara\Remote;
class PodioTask
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
            $info = array('task_id' => $info);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
        Auth::verifyNonApp('task');
        $this->info = Remote::$remote->get('/task/' . $this->info['task_id'])->json_body();
    }

    function delete()
    {
        Auth::verifyNonApp('task');
        return Remote::$remote->delete('/task/' . $this->info['task_id']);
    }

    function __get($var)
    {
        if ($var === 'id') return $this->info['task_id'];
        if (isset($this->info[$var])) {
            return $this->info[$var];
        }
    }

    function save($alert_invite = false)
    {
        throw new \Exception('not implemented yet');
    }

    function __set($var, $value)
    {
        if ($var === 'id') $var = 'task_id';
        $this->info[$var] = $value;
    }

    function __isset($var)
    {
        if ($var === 'id') $var = 'task_id';
        return is_array($this->info) && isset($this->info[$var]);
    }

    function __toString()
    {
        return $this->info['text'];
    }
}