<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Number extends FromTo
{
    function validate($value)
    {
        return (float) $value;
    }
}
