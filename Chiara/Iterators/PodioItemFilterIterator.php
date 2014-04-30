<?php
namespace Chiara\Iterators;
use Chiara\PodioItem as Item, Chiara\PodioApplication as App,
    Chiara\PodioView as View, Chiara\Remote;
class PodioItemFilterIterator implements \ArrayAccess, \Countable, \Iterator
{
    protected $app;
    protected $view;
    protected $count;
    protected $data;
    protected $cursor = 0;
    protected $offset = 0;
    protected $limit = 30;
    function __construct(App $app, View $view = null)
    {
        $this->app = $app;
        $this->view = $view;
    }

    function limit($newlimit)
    {
        $this->limit = (int) $newlimit;
        return $this;
    }

    function getAttributes()
    {
        // TODO: add support for sorting and other view options
        $arr = array();
        if ($this->limit) {
            $arr['limit'] = $this->limit;
        }
        if ($this->offset) {
            $arr['offset'] = $this->offset;
        }
        return $arr;
    }

    protected function setupJIT()
    {
        Auth::prepareRemote($this->app->id);
        $view = '';
        if ($this->view) {
            $view = '/' . $this->view->id;
        }
        $this->data = Remote::$remote->post('/app/filter/' . $this->app->id . $view,
                                            $this->getAttributes())->json_body();
        $this->count = Remote::$remote->get('/item/app/' . $this->app->id . '/count')->json_body();
    }

    function offsetGet($var)
    {
        return new self($this->app, new View($this->app->id, $var));
    }

    function offsetSet($var, $value)
    {
        // TODO: use this to create views
        throw new \Exception('Unimplemented error, cannot set PodioItemFilterIterator value');
    }

    function offsetExists($index)
    {
        try {
            $view = new View($index);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    function offsetUnset($index)
    {
        // TODO: use this to delete views
        throw new \Exception('Unimplemented error, cannot remove PodioItemFilterIterator value');
    }

    function current()
    {
        return PodioItem::factory($this->data[$this->cursor - $this->offset]);
    }

    function key()
    {
        return $this->cursor;
    }

    function next()
    {
        $this->cursor++;
        if ($this->cursor >= $this->count) {
            return;
        }
        if ($this->cursor >= count($this->data) + $this->offset) {
            $this->offset += count($this->data);
            $this->setupJIT();
        }
    }

    function rewind()
    {
        $this->cursor = $this->offset = 0;
        $this->setupJIT();
    }

    function valid()
    {
        return 0 === $this->cursor || $this->cursor < $this->count;
    }
}