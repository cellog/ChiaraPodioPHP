<?php
namespace Chiara\Iterators;
use Chiara\PodioApp as App, Chiara\PodioItem, Chiara\AuthManager as Auth,
    Chiara\Remote;
class AppItemIterator extends \ArrayIterator
{
    protected $app;
    protected $count = 0;
    protected $map = array();
    function __construct(App $app)
    {
        $this->app = $app;
        parent::__construct($app->info['fields']);
        foreach ($this->getArrayCopy() as $i => $field) {
            $this->map[$field['field_id']] = $this->map[$field['external_id']] = $i;
        }
    }

    function count()
    {
        if ($this->count) {
            return $this->count;
        }
        Remote::$remote->prepareRemote($this->app->id);
        $ret = Remote::$remote->get('/item/app/' . $this->app->id . '/count');
        return $this->count = $ret['count'];
    }

    function __get($var)
    {
        if ($var === 'fieldvalues') return new FieldValueIterator($this->app);
    }

}