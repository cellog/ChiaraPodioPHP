<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Fields\IntegerList as Ilist,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class IntegerList extends Ilist
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, array('field_id' => $name));
    }
}
