<?php
namespace Chiara\Iterators;
use Chiara\PodioApp as App;
class AppFieldIterator extends \ArrayIterator
{
    protected $app;
    function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct($app->info['fields']);
    }

    function current()
    {
        $info = parent::current();
        // TODO: return a field object
        return $info;
    }

    function offsetGet($index)
    {
        if (is_int($index)) {
            return $this->offsetGet($index);
        }
        // TODO: return field by name by iterating over the array to find it.  Set up map on the first request
        // to speed up subsequent requests, or perhaps in construction of App object?
    }
}