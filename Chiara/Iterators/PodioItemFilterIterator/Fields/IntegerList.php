<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator as Filter,
    Chiara\Iterators\PodioItemFilterIterator\Field;
abstract class IntegerList extends Field
{
    function add($item)
    {
        $this->filterinfo[] = $this->validate($item);
        $this->filterinfo = array_unique($this->filterinfo);
        $this->saveFilter();
        return $this;
    }

    function validate($value)
    {
        if (!is_int($value)) {
            throw new \Exception('Invalid value, must be an integer');
        }
        return $value;
    }
}
