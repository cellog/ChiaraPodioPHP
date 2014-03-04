<?php
namespace Chiara\AuthManager;
use Chiara\Interfaces\AuthTokenManager;
class File implements AuthTokenManager
{
    protected $filepath;
    protected $apipath;
    protected $info = array();
    protected $client = array();
    protected $map = array();
    function __construct($path, $apipath, $mappath, $create = false)
    {
        $this->filepath = realpath($path);
        if (!$this->filepath) {
            if ($create) {
                file_put_contents($path, json_encode(array(), 1));
                $this->filepath = realpath($path);
            } else {
                throw new \Exception('file ' . $path . ' not found');
            }
        }
        $this->info = json_decode(file_get_contents($this->filepath), 1);
        if (!is_array($this->info)) {
            throw new \Exception('file ' . $path . ' is not a valid json file');
        }

        $this->apipath = realpath($apipath);
        if (!$this->apipath) {
            if ($create) {
                file_put_contents($apipath, json_encode(array(), 1));
                $this->apipath = realpath($apipath);
            } else {
                throw new \Exception('api client file ' . $apipath . ' not found');
            }
        }
        $this->client = json_decode(file_get_contents($this->apipath), 1);
        if (!is_array($this->client)) {
            throw new \Exception('api client file ' . $path . ' is not a valid json file');
        }

        $this->mappath = realpath($mappath);
        if (!$this->mappath) {
            if ($create) {
                file_put_contents($mappath, json_encode(array(), 1));
                $this->mappath = realpath($mappath);
            } else {
                throw new \Exception('class map file ' . $mappath . ' not found');
            }
        }
        $this->map = json_decode(file_get_contents($this->mappath), 1);
        if (!is_array($this->client)) {
            throw new \Exception('class map file ' . $mappath . ' is not a valid json file');
        }
    }

    function getToken($appid)
    {
        if (isset($this->info[$appid])) {
            return $this->info[$appid];
        }
        return false;
    }

    function saveToken($appid, $token)
    {
        $this->info[$appid] = $token;
        file_put_contents($this->filepath, json_encode($this->info, 1));
    }

    function getAPIClient()
    {
        return $this->client;
    }

    function saveAPIClient($client, $token)
    {
        $this->client['client'] = $client;
        $this->client['token'] = $token;
        file_put_contents($this->apipath, json_encode($this->client, 1));
    }

    function mapAppToClass($appid, $class)
    {
        $this->map[$appid] = $class;
        file_put_contents($this->mappath, json_encode($this->map, 1));
    }

    function getAppClass($appid, $defaultClass = 'Chiara\PodioItem')
    {
        return isset($this->map[$appid]) ? $this->map[$appid] : $defaultClass;
    }
}
