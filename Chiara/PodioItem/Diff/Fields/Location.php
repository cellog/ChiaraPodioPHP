<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Collection;
class Location extends CollectionField
{
    protected $itemclass = 'Chiara\\PodioItem\\Values\\Location';

    protected function getId($value)
    {
        return $value['value'];
    }
}