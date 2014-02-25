<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Collection;
class Contact extends CollectionField
{
    protected $itemclass = 'Chiara\\PodioItem\\Values\\Contact';

    protected function getId($value)
    {
        return $value['value']['profile_id'];
    }
}