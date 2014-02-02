<?php
namespace Chiara;
use Podio;
class Remote
{
    static public $remote;

    function get($url, $attributes = array(), $options = array())
    {
        return Podio::get($url, $attributes, $options);
    }

    function post($url, $attributes = array(), $options = array())
    {
        return Podio::post($url, $attributes, $options);
    }

    function authenticate_with_app($app_id, $app_token)
    {
        return Podio::authenticate_with_app($app_id, $app_token);
    }

    function setup($client_id, $client_secret, $options = array('session_manager' => 'PodioSession', 'curl_options' => array()))
    {
        return Podio::setup($client_id, $client_secret, $options);
    }
}
Remote::$remote = new Remote;