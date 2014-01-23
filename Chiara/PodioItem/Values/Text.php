<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioItem, Chiara\PodioItem\Field;
class Text extends Field
{
    function getValue()
    {
        return $this->info['values'][0]['value'];
    }
}
