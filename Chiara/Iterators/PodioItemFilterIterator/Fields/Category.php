<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Category extends IntegerList
{
    function validate($value)
    {
        foreach ($this->info['config']['settings']['options'] as $option) {
            if ($option['id'] == $value || $option['text'] == $value) {
                return $option['id'];
            }
        }
        throw new \Exception('Unknown option "' . $value . '"');
    }
}
