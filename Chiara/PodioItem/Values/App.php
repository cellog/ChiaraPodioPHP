<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class App extends Reference
{
    function retrieveReference()
    {
        return new PodioItem($this->info, null, 'force');
    }

    function getIndices()
    {
        return array(
            $this->info['item_id']
        );
    }
}
