<?php
namespace Chiara\Podio;
class Item extends \Chiara\PodioItem
{
    function __construct($info = null, $retrieve = true)
    {
        parent::__construct($info, new \Chiara\Podio\Testing, $retrieve);
    }
}
