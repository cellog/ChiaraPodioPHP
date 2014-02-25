<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Values\Collection;
class App extends CollectionField
{
    protected $itemclass = 'Chiara\\PodioItem\\Values\\App';

    protected function getId($value)
    {
        return $value['value']['item_id'];
    }
}