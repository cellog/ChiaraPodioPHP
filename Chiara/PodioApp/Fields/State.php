<?php
namespace Chiara\PodioApp\Fields;
use Chiara\PodioApp\Field;
class State extends Field
{
    function __construct(array $info = array())
    {
        parent::__construct($info);
        if (!count($info)) {
            $this->info['config']['settings']['allowed_values'] = array();
        }
    }

    function __get($var)
    {
        if ($var == 'allowed_values') return $this->info['config']['settings']['allowed_values'];
        return parent::__get($var);
    }
}