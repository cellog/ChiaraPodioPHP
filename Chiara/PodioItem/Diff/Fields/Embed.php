<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Collection;
class Embed extends CollectionField
{
    protected $itemclass = 'Chiara\\PodioItem\\Values\\Embed';

    protected function getId($value)
    {
        return $value['embed']['embed_id'];
    }
}