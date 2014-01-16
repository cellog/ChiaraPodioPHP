<?php
namespace Chiara\Iterators;
use Chiara\PodioApp as App, Chiara\PodioApp\Field;
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
        return Field::newField($info);
    }

    function offsetGet($index)
    {
        if (is_int($index) && $index < 30) {
            return $this->offsetGet($index);
        }
        $info = parent::offsetGet($this->map[$index]);
        return Field::newField($info);
    }
}