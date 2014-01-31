<?php
namespace Chiara\AuthManager;
class File implements TokenInterface
{
    protected $filepath;
    protected $info = array();
    function __construct($path)
    {
        $this->filepath = realpath($path);
        if (!$this->filepath) {
            throw new \Exception('file ' . $path . ' not found');
        }
        $this->info = json_decode(file_get_contents($this->filepath), 1);
        if (!is_array($this->info)) {
            throw new \Exception('file ' . $path . ' is not a valid json file');
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
}
