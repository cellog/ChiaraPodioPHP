<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
class Duration extends Number
{
    function __construct(App $app, View $view, $info)
    {
        parent::__construct($app, $view, $info);
    }

    function validate($value)
    {
        $value = parent::validate($value);
        if ($value < 0) {
            throw new \Exception('invalid duration "' . $value . '", must be > 0');
        }
        return (int) $value;
    }
}
