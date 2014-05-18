<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class App extends IntegerList
{
    protected $objname = 'PodioApp';
    function validate($value)
    {
        if (is_object($value)) {
            $value = $value->id;
        }
        if (is_array($value)) {
            if (isset($value['item_id'])) {
                $value = $value['item_id'];
            } else {
                throw new \Exception('Cannot use indeterminate array as a filter');
            }
        }
        if (!is_int($value)) {
            throw new \Exception('Can only use an integer, a ' . $this->objname . ' object, or a json array from the API as a filter');
        }
        return $value;
    }
}
