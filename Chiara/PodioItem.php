<?php
namespace Chiara;
class PodioItem
{
    private $config = array(
        
    );
    /**
     * The PodioApplicationStructure that defines this item's structure
     *
     * Note that fields may exist that are not in the definition (legacy deleted fields)
     * @var Chiara\PodioApplicationStructure
     */
    protected $app;
}