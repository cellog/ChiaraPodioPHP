<?php
namespace Chiara\Iterators;
use Chiara\PodioItem, Chiara\PodioItem\Field;

class ItemFieldIterator extends \ArrayIterator
{
    protected $item;
    protected $map = array();
    protected $reverse_map = array();
    function __construct(PodioItem $item)
    {
        $this->item = $item;
        parent::__construct($f = $item->info['fields']);
        foreach ($f as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
            $this->reverse_map[$i] = $field['external_id'];
        }
    }

    function current()
    {
        $info = parent::current();
        return Field::newField($this->item, $info);
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            if (!isset($this[$index])) {
                throw new \Exception("Unknown field \"" . $index . "\"");
            }
            $info = parent::offsetGet($index);
        } else {
            if (!isset($this->map[$index])) {
                throw new \Exception("Unknown field \"" . $index . "\"");
            }
            $info = parent::offsetGet($this->map[$index]);
        }
        $a = $this->item;
        return Field::newField($this->item, $info);
    }

    function offsetSet($index, $value)
    {
        if (is_int($index) && isset($this->reverse_map[$index])) {
            $index = $this->reverse_map[$index];
        }
        $this->item->setFieldValue($index, $value);
    }

    function offsetExists($index)
    {
        if (is_int($index) && $index < 30) {
            if (!isset($this[$index])) {
                return false;
            }
        } else {
            if (!isset($this->map[$index])) {
                return false;
            }
        }
        return true;
    }
}