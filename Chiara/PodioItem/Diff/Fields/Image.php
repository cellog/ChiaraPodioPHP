<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Collection;
class Image extends CollectionField
{
    protected $itemclass = 'Chiara\\PodioItem\\Values\\Image';

    protected function getId($value)
    {
        return $value['value']['file_id'];
    }
}