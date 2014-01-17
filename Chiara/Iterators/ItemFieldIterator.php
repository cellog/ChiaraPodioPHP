<?php
namespace Chiara\Iterators;
use Chiara\PodioItem;

class ItemFieldIterator extends ArrayIterator
{
    protected $item;
    protected $map = array();
    function __construct(PodioItem $item)
    {
        $this->item = $item;
        parent::__construct($item->info['fields']);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            return $this->offsetGet($index);
        }
        $info = parent::offsetGet($this->map[$index]);
    }

    function offsetSet($index, $value)
    {
        if (is_int($index) && $index > 30) {
            $index = $this->map[$index];
        }
        $this->item->setFieldByIndex($index, $value);
    }
}