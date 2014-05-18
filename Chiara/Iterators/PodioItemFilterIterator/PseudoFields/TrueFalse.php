<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Field,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class TrueFalse extends Field
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, $name);
        $this->info = array('field_id' => $name);
    }

    function isTrue()
    {
        $this->filterinfo['values'] = true;
        $this->saveFilter();
    }

    function isFalse()
    {
        $this->filterinfo['values'] = false;
        $this->saveFilter();
    }
}
