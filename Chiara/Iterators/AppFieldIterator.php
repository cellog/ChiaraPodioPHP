<?php
namespace Chiara\Iterators;
use Chiara\PodioApp as App;
class AppFieldIterator extends \ArrayIterator
{
    protected $app;
    protected $map = array();
    function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct($app->info['fields']);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
        }
    }

    function current()
    {
        $info = parent::current();
        // TODO: return a field object
        return $info;
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            return $this->offsetGet($index);
        }
        $info = parent::offsetGet($this->map[$index]);
        // TODO: return a field object
        return $info;
    }
}