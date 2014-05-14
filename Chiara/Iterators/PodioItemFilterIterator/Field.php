<?php
namespace Chiara\Iterators\PodioItemFilterIterator;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator\Field;
class Field
{
    protected $app;
    protected $filter;
    protected $info;
    function __construct(App $app, Filter $filter, array $info)
    {
        $this->app = $app;
        $this->filter = $filter;
        $this->info = $info;
    }

    function type()
    {
        return $this->info['type'];
    }

    function newField(App $app, Filter $filter, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($app, $filter, $info);
    }
}