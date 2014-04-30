<?php
namespace Chiara\Iterators;
use Chiara\PodioItem as Item, Chiara\PodioItem\Values\ReferenceCollection;
class ReferenceIterator extends \ArrayIterator
{
    protected $item;
    protected $map = array();
    function __construct(Item $item, $refs)
    {
        $this->item = $item;
        parent::__construct($refs);
        foreach ($refs as $i => $info) {
            $this->map[$info['app']['app_id']] = $this->map[$info['app']['url_label']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return new ReferenceCollection($this->item, $info['app'], $info['items']);
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return new ReferenceCollection($this->item, $info['app'], $info['items']);
    }

    function offsetExists($index)
    {
        if (is_int($index) && $index < 30) {
            return parent::offsetExists($index);
        } else {
            return parent::offsetExists($this->map[$index]);
        }
    }
}