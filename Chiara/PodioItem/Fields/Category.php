<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection, Chiara\PodioItem\Values\Option;
class Category extends Field
{
    function getValue()
    {
        if (!$this->info['config']['settings']['multiple']) {
            if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
                return null;
            }
            return new Option($this->parent, $this->info['values'][0]['value']);
        }
        if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
            return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Option');
        }
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Option');
    }

    function __get($var)
    {
        if ($var == 'options') return $this->info['config']['settings']['options'];
        if ($var == 'multiple') return $this->info['config']['settings']['multiple'];
        return parent::__get($var);
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