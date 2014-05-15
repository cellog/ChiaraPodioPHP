<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
class Progress extends FromTo
{
    function __construct(App $app, View $view, $info)
    {
        parent::__construct($app, $view, $info);
    }

    function validate($value)
    {
        if ($value >= 0 || $value <= 100) {
            return (int) $value;
        }
        throw new \Exception('invalid progress value "' . $value . '"');
    }
}
