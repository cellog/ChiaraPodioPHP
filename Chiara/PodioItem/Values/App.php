<?php
namespace Chiara\PodioItem\Values;
use Chiara\AuthManager as Auth;
class App extends Reference
{
    protected $myapp = null;
    function retrieveReference()
    {
        if ($this->myapp) return $this->myapp;
        $class = Auth::getTokenManager()->getAppClass($this->info['value']['app']['app_id']);
        return $this->myapp = new $class($this->info['value'], null, 'force');
    }

    function extendedGet($var)
    {
        if ($var == 'fields' || $var == 'structure' || $var == 'app' || $var == 'info') {
            return $this->retrieveReference()->__get($var);
        }
        if ($var == 'id') {
            return $this->info['value']['item_id'];
        }
        return parent::extendedGet($var);
    }

    function getIndices()
    {
        return array(
            $this->info['value']['item_id']
        );
    }

    function saveValue()
    {
        return $this->info['value']['item_id'];
    }
}
