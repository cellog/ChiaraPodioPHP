<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Fields\Field;
class Rating extends Field
{
    function __construct(App $app, View $view, $name)
    {
        parent::__construct($app, $view, $name);
        $this->info = array('field_id' => $name);
    }

    function add($value)
    {
        $this->filterinfo[] = $value;
        $this->saveFilter();
    }
}
