<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Duration extends Number
{
    function validate($value)
    {
        $value = parent::validate($value);
        if ($value < 0) {
            throw new \Exception('invalid duration "' . $value . '", must be > 0');
        }
        return (int) $value;
    }
}
