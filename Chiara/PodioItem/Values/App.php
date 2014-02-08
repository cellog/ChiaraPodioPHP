<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem;
class App extends Reference
{
    function retrieveReference()
    {
        return new PodioItem($this->info['value'], null, 'force');
    }

    function extendedGet($var)
    {
        if ($var == 'fields' || $var == 'structure' || $var == 'app' || $var == 'info') {
            return $this->getValue()->__get($var);
        }
        if ($var == 'id') {
            return $this->info['value']['item_id'];
        }
        return parent::extendedGet($var);
    }

    function getIndices()
    {
        return array(
            $this->info['value']['item_id']
        );
    }

    function saveValue()
    {
        return $this->info['value']['item_id'];
    }
}
