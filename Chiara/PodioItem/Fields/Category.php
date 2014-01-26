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
}