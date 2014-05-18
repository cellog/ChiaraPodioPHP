<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Field,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class Rating extends Field
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, $name);
        $this->info = array('field_id' => $name);
    }

    function add($value)
    {
        $this->filterinfo[] = $value;
        $this->saveFilter();
    }
}
