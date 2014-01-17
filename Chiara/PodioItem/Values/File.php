<?php
namespace Chiara\PodioItem\Values;

/**
 * Used for handling files, either local files to upload or remote files
 * as the value of a field in an item
 */
class File
{
    protected $remote = false;
    protected $local_path = null;
    protected $remote_id = null;
    protected $info;

    function __construct($filename, $localpath = null)
    {
        $this->local_path = $localpath;
        if (is_int($filename)) {
            $this->remote_id = $filename;
            $this->remote = true;
        } elseif (is_string($filename)) {
            $this->filename = $filename;
            if (!$this->local_path || !is_file($this->local_path)) {
                // TODO: use a Chiara-specific exception
                throw new \Exception($localpath . ' is not a valid file');
            }
        } elseif (is_array($filename)) {
            $this->info = $filename;
            $this->remote_id = $filename['file_id'];
            $this->remote = true;
        }
    }

    function save()
    {
        if (!$this->remote) {
            // we need to upload the file
            $this->info = Podio::post("/file/v2/",
                            array('source' => '@' . $this->local_path, 'filename' => $this->filename),
                            array('upload' => true, 'filesize' => filesize($this->local_path)));
        }
    }

    function download()
    {
        if (!$this->remote) return;
        file_put_contents($this->local_path, Podio::get($this->info['link'], array(), array('file_download' => true))->body);
    }
}