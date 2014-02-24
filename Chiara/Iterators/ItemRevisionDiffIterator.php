<?php
namespace Chiara\Iterators;
use Chiara\PodioItem, Chiara\PodioItem\Diff\Field;

class ItemRevisionDiffIterator extends ItemFieldIterator
{
    protected $map = array();
    protected $reverse_map = array();
    function __construct(PodioItem $item, array $diff)
    {
        $this->item = $item;
        \ArrayIterator::__construct($diff);
        foreach ($diff as $i => $field) {
            // TODO: use the app structure to retrieve external_id for the field_id
            $e = $item->getFieldName($field['field_id']);
            $this->map[$field['field_id']] = $i;
            if ($e) {
                $this->map[$e] = $i;
                $this->reverse_map[$i] = $e;
            }
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
        throw new \Exception('Cannot modify an item revision diff');
    }
}