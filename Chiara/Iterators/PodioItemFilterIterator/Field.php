<?php
namespace Chiara\Iterators\PodioItemFilterIterator;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator\Field,
    Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
class Field
{
    protected $app;
    protected $view;
    protected $filter;
    protected $info;
    /**
     * Used to store information by child classes for passing a new filter
     * upstream to the parent Filter object.
     */
    protected $filterinfo = array();
    function __construct(App $app, Filter $filter, array $info)
    {
        $this->app = $app;
        $this->view = $filter->view;
        $this->filterinfo = $this->view->getFilter($info['field_id']);
        $this->filter = $filter;
        $this->info = $info;
    }

    function type()
    {
        return $this->info['type'];
    }

    function sortBy($desc = true)
    {
        $this->view->sort($this->info['field_id'], $desc);
    }

    protected function saveFilter()
    {
        if (count($this->filterinfo)) {
            $this->view->setFilter($this->info['field_id'], $this->filterinfo);
        }
    }

    static function newPseudoField(App $app, Filter $filter, $name, $classname)
    {
        $class = __NAMESPACE__ . '\\PseudoFields\\' . $classname;
        return new $class($app, $filter, $name);
    }

    static function newField(App $app, Filter $filter, array $info)
    {
        $class = __NAMESPACE__ . '\\Fields\\' . ucfirst($info['type']);
        return new $class($app, $filter, $info);
    }
}