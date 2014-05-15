<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
class Category extends IntegerList
{
    function __construct(App $app, View $view, $info)
    {
        parent::__construct($app, $view, $info);
    }

    function validate($value)
    {
        foreach ($this->info['config']['settings']['options'] as $option) {
            if ($option['id'] == $value || $option['text'] == $value) {
                return $option['id'];
            }
        }
        throw new \Exception('Unknown option "' . $value . '"');
    }
}
