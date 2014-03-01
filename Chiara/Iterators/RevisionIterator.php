<?php
namespace Chiara\Iterators;
use Chiara\PodiRevision as Revision, Chiara\PodioItem as Item;
class RevisionIterator extends \ArrayIterator
{
    protected $item;
    function __construct(Item $item)
    {
        $this->item = $item;
        parent::__construct($item->getRevisions());
    }

    function current()
    {
        $info = parent::current();
        return new Revision($this->item, $info);
    }

    function offsetGet($index)
    {
        $info = parent::offsetGet($index);
        return new Revision($this->item, $info);
    }
}