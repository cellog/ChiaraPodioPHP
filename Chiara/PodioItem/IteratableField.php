<?php
namespace Chiara\PodioItem;
use Chiara\PodioItem;
class IteratableField extends Field
{
    function __construct(PodioItem $parent, array $info = array())
    {
        parent::__construct($parent, $info, true);
    }
}