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

    function expectRequest($type, $url, $result, $attributes = array())
    {
        $type = strtoupper($type);
        $this->map[$type][$url . '?' . http_build_query($attributes)] = $result;
    }

    function getReturn($url, $type)
    {
        if (isset($this->map[$type]) && isset($this->map[$type][$url])) {
            $response = new PodioResponse();
            $response->body = $this->map[$type][$url];
            $response->status = 200;
            return $response;
        }
        throw new \Exception('Unexpected ' . $type . ': ' . $url);
    }

    function get($url, $attributes = array(), $options = array())
    {
        $this->queries[] = array('get', array($url, $attributes, $options));
        return $this->getReturn($url . '?' . http_build_query($attributes), 'GET');
    }

    function delete($url, $attributes = array())
    {
        $this->queries[] = array('delete', array($url, $attributes));
        return $this->getReturn($url . '?' . http_build_query($attributes), 'DELETE');
    }

    function post($url, $attributes = array(), $options = array())
    {
        $this->queries[] = array('post', array($url, $attributes, $options));
        return $this->getReturn($url . '?' . http_build_query($attributes), 'POST');
    }

    function put($url, $attributes = array())
    {
        $this->queries[] = array('post', array($url, $attributes, $options));
        return $this->getReturn($url . '?' . http_build_query($attributes), 'PUT');
    }

    function authenticate_with_app($app_id, $app_token)
    {
        $this->queries[] = array('authenticate_with_app' => array($app_id, $app_token));
    }

    function authenticate($grant_type, $attributes)
    {
        $this->queries[] = array('authenticate' => array($grant_type, $attributes));
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