<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Diff\Field;
class Number extends Field
{
    function getOldValue()
    {
        return (float) parent::getOldValue();
    }

    function getValue()
    {
        return (float) parent::getValue();
    }
}