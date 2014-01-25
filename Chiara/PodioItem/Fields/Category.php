<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection;
class Category extends Field
{
    function getValue()
    {
        if (!$this->info['config']['multiple']) {
            if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
                return null;
            }
            return new Option($this->info['values'][0]);
        }
        if (!isset($this->info['values']) || !isset($this->info['values'][0])) {
            return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Option');
        }
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Option');
    }
}