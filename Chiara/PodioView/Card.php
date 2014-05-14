<?php
namespace Chiara\PodioView;
use Chiara\PodioView;
class Card extends PodioView
{
    function __construct($appid, $viewid = null, $retrieve = true)
    {
        parent::__construct($appid, $viewid, $retrieve);
    }

    function setXAxisField($field)
    {
        $field = $this->app->fields[$field];
        $this->setField($field->id, false, true, false);
    }

    function setYAxisField($field)
    {
        $field = $this->app->fields[$field];
        $this->setField($field->id, false, false, true);
    }
}
