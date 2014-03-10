<?php
namespace Chiara\Iterators;
use Chiara\PodioItem as Item;
class TopItemIterator extends \ArrayIterator
{
    protected $items;
    protected $map = array();
    function __construct(array $items)
    {
        $this->items = $items;
        parent::__construct($items);
        foreach ($items as $i => $item) {
            $this->map[$item['item_id']] = $this->map[$field['item_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return Item::factory($info);
    }

    function offsetGet($index)
    {
        if (isset($this->map[$index])) {
            $info = $this->map[$index];
        } else {
            $info = parent::offsetGet($index);
        }
        return Item::factory($info);
    }

    function offsetExists($index)
    {
        return isset($this->map[$index]) || parent::offsetExists($index);
    }
}