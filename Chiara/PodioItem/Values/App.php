<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class App extends Reference
{
    function retrieveReference()
    {
        return new PodioItem($this->info['value'], null, 'force');
    }

    function getIndices()
    {
        return array(
            $this->info['value']['item_id']
        );
    }
}
