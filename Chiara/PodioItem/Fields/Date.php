<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Date as Value;
class Date extends Field
{
    function getValue()
    {
        return new Value(parent::getValue());
    }
}