<?php
namespace Chiara\Iterators;
use Chiara\PodioItem as Item, Chiara\PodioApp as App,
    Chiara\PodioView as View, Chiara\Remote, Chiara\AuthManager as Auth,
    Chiara\Iterators\PodioItemFilterIterator\Fields;
class PodioItemFilterIterator implements \ArrayAccess, \Countable, \Iterator
{
    protected $app;
    protected $view;
    protected $count;
    protected $data = array();
    protected $cursor = 0;
    protected $offset = 0;
    protected $limit = 30;
    protected $remember = false;
    function __construct(App $app, View $view = null)
    {
        $this->app = $app;
        $this->view = $view;
    }

    function getView()
    {
        return $this->view;
    }

    function limit($newlimit)
    {
        $this->limit = (int) $newlimit;
        return $this;
    }

    function offset($newoffset)
    {
        $this->offset = (int) $newoffset;
        return $this;
    }

    function remember()
    {
        $this->remember = true;
        return $this;
    }

    function forget()
    {
        $this->remember = false;
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
        if ($this->remember) {
            $arr['remember'] = '1';
        }
        return $arr;
    }

    protected function setupJIT()
    {
        Auth::prepareRemote($this->app->id);
        $view = '';
        if ($this->view && $this->view->id) {
            $view = '/' . $this->view->id;
        }
        $this->data = Remote::$remote->post('/item/app/' . $this->app->id . '/filter/' . $view,
                                            $this->getAttributes())->json_body();
        $this->count = $this->data['filtered'];
    }

    function __get($var)
    {
        if ($var === 'fields') {
            return new Fields($this->app, $this);
        }
        if ($var === 'pseudofields') {
            return new Fields($this->app, $this, true);
        }
        if ($var === 'view') {
            if (!$this->view) {
                $this->view = new View($this->app);
            }
            return $this->view;
        }
        if ($var === 'by') {
            $this->by($var);
            return $this;
        }
    }

    function by($var)
    {
        if (is_string($var) || is_int($var)) {
            $var = new View($this->app, $var);
        }
        if ($var instanceof View) {
            $this->view = $var;
        }
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
        if (!count($this->data)) {
            $this->setupJIT();
        }
        if (!count($this->data['items'])) {
            return null;
        }
        return Item::factory($this->data['items'][$this->cursor - $this->offset]);
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
        if ($this->cursor >= count($this->data['items']) + $this->offset) {
            $this->offset += count($this->data['items']);
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

    function count()
    {
        if (!count($this->data)) {
            $this->setupJIT();
        }
        return $this->count;
    }
}