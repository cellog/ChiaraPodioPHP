<?php
namespace Chiara\PodioView;
use Chiara\PodioView;
class Card extends PodioView
{
    function __construct($appid, $viewid = null, $retrieve = true)
    {
        parent::__construct($appid, $viewid, $retrieve);
    }

    function setFieldWidth($field, $width)
    {
        $field = $this->app->fields[$field];
        $old = $this->getField($field);
        if ($old) {
            $hidden = $old['hidden'];
        } else {
            $hidden = false;
        }
        $this->setField($field->id, $hidden, false, false, (int) $width);
    }

    function hideField($field)
    {
        $field = $this->app->fields[$field];
        $old = $this->getField($field);
        if ($old) {
            $width = $old['width'];
        } else {
            $width = null;
        }
        $this->setField($field->id, true, false, false, $width);
    }

    function showField($field)
    {
        $field = $this->app->fields[$field];
        $old = $this->getField($field);
        if ($old) {
            $width = $old['width'];
        } else {
            $width = null;
        }
        $this->setField($field->id, false, false, false, $width);
    }
}
