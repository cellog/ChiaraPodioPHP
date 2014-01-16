<?php
namespace Chiara\PodioApp\Fields;
use Chiara\PodioApp\Field;
class App extends Field
{
    function __construct(array $info = array())
    {
        parent::__construct($info);
        if (!count($info)) {
            $this->info['config']['settings']['referenceable_types'] = array();
        }
    }

    function __get($var)
    {
        if ($var == 'referenceable_types') return $this->info['config']['settings']['referenceable_types'];
        return parent::__get($var);
    }
}