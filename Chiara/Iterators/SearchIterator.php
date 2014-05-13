<?php
namespace Chiara\Iterators;
use Chiara\PodioItem, Chiara\PodioTask, Chiara\PodioStatus;

class SearchIterator extends \ArrayIterator
{
    protected $map = array();
    protected $filtertype = null;
    function __construct(array $results, $filtertype = null)
    {
        parent::__construct($results);
        $this->filtertype = null;
        foreach ($results as $i => $info) {
            $this->map[$info['type']][$info['id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return $this->convert($info);
    }

    protected function convert(array $info)
    {
        switch ($info['type']) {
            case 'item':
                return new PodioItem($info['id']);
            case 'status':
                return new PodioStatus($info['id']);
            case 'task':
                return new PodioTask($info['id']);
        }
    }

    function offsetGet($index)
    {
        $info = parent::offsetGet($index);
        return $this->convert($info);
    }

    function __get($var)
    {
        if ($var === 'item' || $var === 'status' || $var === 'task') {
            $new = array();
            foreach ($this->map[$var] as $index) {
                $new[] = parent::offsetGet($index);
            }
            return new self($new, $var);
        }
    }

    function offsetExists($index)
    {
        return parent::offsetExists($index);
    }
}