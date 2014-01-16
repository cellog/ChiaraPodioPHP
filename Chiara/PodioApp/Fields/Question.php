<?php
namespace Chiara\PodioApp\Fields;
use Chiara\PodioApp\Field;
class Question extends Field
{
    function __construct(array $info = array())
    {
        parent::__construct($info);
        if (!count($info)) {
            $this->info['config']['settings']['options'] = array();
            $this->info['config']['settings']['multiple'] = false;
        }
    }

    function __get($var)
    {
        if ($var == 'options') return $this->info['config']['settings']['options'];
        if ($var == 'multiple') return $this->info['config']['settings']['multiple'];
        return parent::__get($var);
    }
}