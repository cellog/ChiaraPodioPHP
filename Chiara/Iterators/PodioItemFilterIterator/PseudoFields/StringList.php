<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Fields\IntegerList as Ilist,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class StringList extends Ilist
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, $name);
        $this->info = array('field_id' => $name);
    }

    function validate($value)
    {
        if (!is_string($value)) {
            throw new \Exception('Invalid value type "' . gettype($value) . '", must be a string');
        }
        return $value;
    }
}
