<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
class Money extends FromTo
{
    function __construct(App $app, View $view, $info)
    {
        parent::__construct($app, $view, $info);
    }
}
