<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Progress extends FromTo
{
    function validate($value)
    {
        if ($value >= 0 && $value <= 100) {
            return (int) $value;
        }
        throw new \Exception('invalid progress value "' . $value . '"');
    }
}
