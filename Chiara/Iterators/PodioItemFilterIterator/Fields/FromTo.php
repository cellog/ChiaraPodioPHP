<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App,
    Chiara\Iterators\PodioItemFilterIterator\Field,
    Chiara\Iterators\PodioItemFilterIterator as Filter;
abstract class FromTo extends Field
{
    function __construct(App $app, Filter $filter, $info)
    {
        parent::__construct($app, $filter, $info);
    }

    function from($value)
    {
        $value = $this->validate($value);
        $this->filterinfo['from'] = $value;
        if (!isset($this->filterinfo['to'])) {
            $this->filterinfo['to'] = null;
        }
        $this->saveFilter();
        return $this;
    }

    function to($value)
    {
        $value = $this->validate($value);
        if (!isset($this->filterinfo['from'])) {
            $this->filterinfo['from'] = null;
        }
        $this->filterinfo['to'] = $value;
        $this->saveFilter();
        return $this;
    }

    protected function saveFilter()
    {
        $this->view->setFilter($this->info['field_id'], $this->filterinfo);
    }

    abstract function validate($value);
}
