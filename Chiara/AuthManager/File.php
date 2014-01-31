<?php
namespace Chiara\AuthManager;
class File implements TokenInterface
{
    protected $filepath;
    protected $apipath;
    protected $info = array();
    protected $client = array();
    function __construct($path, $apipath, $create = false)
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
                $this->apipath = realpath($path);
            } else {
                throw new \Exception('api client file ' . $path . ' not found');
            }
        }
        $this->client = json_decode(file_get_contents($this->apipath), 1);
        if (!is_array($this->client)) {
            throw new \Exception('api client file ' . $path . ' is not a valid json file');
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
}
