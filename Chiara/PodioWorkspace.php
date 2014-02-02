<?php
namespace Chiara;
use Chiara\Iterators\WorkspaceAppIterator, Chiara\Remote;
class PodioWorkspace
{
    protected $info;
    protected $apps = array();
    function __construct($info = null)
    {
        if (is_int($info)) {
            $this->info = array('space_id' => $info);
            return;
        }
        $this->info = $info;
    }

    function getApps($include_inactive = false)
    {
        if (count($this->apps)) {
            return $this->apps;
        }
        Auth::verifyNonApp('workspace');
        $this->apps = Remote::$remote->get('/app/space/' . $this->info['space_id'], array('include_inactive' => $include_inactive))->json_body;
        return $this->apps;
    }

    function __get($var)
    {
        if ($var === 'apps') {
            return new WorkspaceAppIterator($this);
        }
    }

    function __set($var, $value)
    {
        if ($var === 'apps') {
            $this->apps = $value;
        }
    }
}