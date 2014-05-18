<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Fields\Date as D,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class Date extends D
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, array('field_id' => $name));
    }
}
