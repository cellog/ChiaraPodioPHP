<?php
class TestRemote extends Chiara\Remote
{
    public $queries = array();
    public $auth = array();
    /**
     * a map of queries to the result it should return
     */
    public $map = array();

    function reset()
    {
        $this->queries = $this->auth = $this->map = array();
    }
    function get($url, $attributes = array(), $options = array())
    {
        $this->queries[] = array('get', array($url, $attributes, $options));
    }

    function post($url, $attributes = array(), $options = array())
    {
        $this->queries[] = array('post', array($url, $attributes, $options));
    }

    function authenticate_with_app($app_id, $app_token)
    {
        $this->queries[] = array('authenticate_with_app' => array($app_id, $app_token));
    }

    function setup($client_id, $client_secret, $options = array('session_manager' => 'PodioSession', 'curl_options' => array()))
    {
        $this->queries[] = array('setup' => array($client_id, $client_secret, $options));
    }

    function passwordAuth()
    {
        if (!file_exists(__DIR__ . '/testuser.json')) {
            throw new \Exception('You need to create testuser.json in ' . __DIR__ . ' with format: {"username":"username","password":"password"}');
        }
        $a = json_decode(file_get_contents(__DIR__ . '/testuser.json'));
        Podio::authenticate('password', $a);
    }
}
Chiara\Remote::$remote = new TestRemote;