<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection;
class Contact extends Field
{
    function getValue()
    {
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Contact');
    }
}