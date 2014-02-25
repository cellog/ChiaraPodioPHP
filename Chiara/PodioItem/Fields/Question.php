<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Values\Option;
class Question extends Category
{
    function getValue()
    {
        if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
            return null;
        }
        return new Option($this->parent, $this->info['values'][0]['value']);
    }

    function getSaveValue()
    {
        $value = $this->getValue();
        if ($this->info['config']['settings']['multiple']) {
            $ret = array();
            foreach ($value as $v) {
                $ret[] = $v->id;
            }
            return $ret;
        } else {
            return $value->id;
        }
    }
}