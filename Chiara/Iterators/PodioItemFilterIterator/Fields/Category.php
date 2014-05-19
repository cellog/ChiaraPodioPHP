<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Category extends IntegerList
{
    function validate($value)
    {
        if (is_object($value)) {
            $value = $value->id;
        }
        if (is_array($value)) {
            $value = $value['id'];
        }
        if (!is_int($value) && !is_string($value)) {
            throw new \Exception('Invalid category option value type: ' . gettype($value));
        }
        foreach ($this->info['config']['settings']['options'] as $option) {
            if ($option['id'] === $value || $option['text'] === $value) {
                return $option['id'];
            }
        }
        throw new \Exception('Unknown option "' . $value . '"');
    }
}
