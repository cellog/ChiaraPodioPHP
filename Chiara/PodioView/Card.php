<?php
namespace Chiara\PodioView;
use Chiara\PodioView, Chiara\PodioApp as App;
class Card extends PodioView
{
    function __construct(App $app, $viewid = null, $retrieve = true)
    {
        parent::__construct($app, $viewid, $retrieve);
    }

    function groupRowsBy($field)
    {
        $field = $this->app->fields[$field];
        $this->setField($field->id, false, true, false);
        return $this;
    }

    function groupColsBy($field)
    {
        $field = $this->app->fields[$field];
        $this->setField($field->id, false, false, true);
        return $this;
    }
}
