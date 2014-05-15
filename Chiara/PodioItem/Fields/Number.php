<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field;
class Number extends Field
{
    function getValue()
    {
        if (null == $a = parent::getValue()) {
            return null;
        }
        return (float) $a;
    }
}