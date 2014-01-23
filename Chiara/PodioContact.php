<?php
namespace Chiara;
class PodioContact
{
    protected $info;

    function __construct($info = null, $retrieve = true)
    {
        $this->info = $info;
        if (is_array($info) && $retrieve !== 'force') {
            $this->info = $info;
            return;
        }
        if (is_int($info)) {
            $info = array('user_id' => $info);
        }
        if (!$retrieve) return;
        $this->retrieve();
    }

    function retrieve()
    {
    }
}