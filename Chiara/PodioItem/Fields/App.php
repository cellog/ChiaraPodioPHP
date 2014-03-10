<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection, Chiara\PodioApp\Field\App;
class App extends Field
{
    function getValue()
    {
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\App');
    }

    function getSaveValue()
    {
        $value = $this->getValue();
        $ret = array();
        foreach ($value as $v) {
            $ret[] = $v->id;
        }
        return $ret;
    }

    function topValues($limit = 13, array $excludeItems = array())
    {
        Auth::prepareRemote($this->parent->id);
        return App::topValues($this->id, $limit, $excludeItems);
    }

    function __get($var)
    {
        if ($var == 'referenceable_types') return $this->info['config']['settings']['referenceable_types'];
        return parent::__get($var);
    }
}