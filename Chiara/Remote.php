<?php
namespace Chiara;
use Podio;
class Remote
{
    static public $remote;

    function mergeOptions($attributes, $options)
    {
        if (isset($options['hook'])) {
            $attributes['hook'] = $options['hook'] ? 'true' : 'false';
        }
        if (isset($options['silent'])) {
            $attributes['silent'] = $options['silent'] ? '1' : '0';
        }
        if (isset($options['fields'])) {
            $attributes['fields'] = $options['fields'];
        }
        return $attributes;
    }

    function get($url, $attributes = array(), $options = array())
    {
        return Podio::get($url, $this->mergeOptions($attributes, $options));
    }

    function post($url, $attributes = array(), $options = array())
    {
        return Podio::post($url, $this->mergeOptions($attributes, $options));
    }

    function put($url, $attributes = array(), $options = array())
    {
        return Podio::put($url, $this->mergeOptions($attributes, $options));
    }

    function delete($url, $attributes = array())
    {
        return Podio::delete($url, $attributes);
    }

    function authenticate_with_app($app_id, $app_token)
    {
        return Podio::authenticate_with_app($app_id, $app_token);
    }

    function authenticate_with_password($username, $password)
    {
        return Podio::authenticate('password', array('username' => $username, 'password' => $password));
    }

    function authenticate($grant_type, $attributes)
    {
        return Podio::authenticate($grant_type, $attributes);
    }

    function setup($client_id, $client_secret, $options = array('session_manager' => 'PodioSession', 'curl_options' => array()))
    {
        return Podio::setup($client_id, $client_secret, $options);
    }
}
Remote::$remote = new Remote;