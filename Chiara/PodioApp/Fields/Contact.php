<?php
namespace Chiara\PodioApp\Fields;
use Chiara\PodioApp\Field;
class Contact extends Field
{
    function __construct(PodioApp $parent, array $info = array())
    {
        parent::__construct($parent, $info);
        if (!count($info)) {
            $this->info['config']['settings']['type'] = 'space_users';
        }
    }

    function __get($var)
    {
        if ($var == 'contact_type') return $this->info['config']['settings']['type'];
        return parent::__get($var);
    }
}