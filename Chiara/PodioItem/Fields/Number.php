<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field;
class Number extends Field
{
    function getValue()
    {
        return (float) parent::getValue();
    }
}