<?php
namespace Chiara\Iterators;
use Chiara\PodioView as View, Chiara\PodioApp as App;
class OrganizationIterator extends \ArrayIterator
{
    protected $map = array();
    protected $app;
    function __construct(App $parent, array $views)
    {
        parent::__construct($views);
        $this->app = $parent;
        foreach ($orginfo as $i => $view) {
            $this->map[$view['name']] = $this->map[$field['view_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        return new View($this->app, $info);
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            $info = parent::offsetGet($index);
        } else {
            $info = parent::offsetGet($this->map[$index]);
        }
        return new View($this->app, $info);
    }
}