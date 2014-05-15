<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator as Filter,
    Chiara\Iterators\PodioItemFilterIterator\Field;
abstract class IntegerList extends Field
{
    function __construct(App $app, Filter $filter, $info)
    {
        parent::__construct($app, $filter, $info);
    }

    function add($item)
    {
        $this->filterinfo[] = $this->validate($item);
        $this->saveFilter();
    }

    function validate($value)
    {
        if (!is_int($value)) {
            throw new \Exception('Invalid value, must be an integer');
        }
        return $value;
    }
}
