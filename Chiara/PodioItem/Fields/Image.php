<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\IteratableField, Chiara\PodioItem\Values\Collection;
class Image extends IteratableField
{
    function getValue()
    {
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Image');
    }

    function getSaveValue()
    {
        $value = $this->getValue();
        $ret = array();
        foreach ($value as $v) {
            $ret[] = $v->id;
        }
        return $ret;
    }
}