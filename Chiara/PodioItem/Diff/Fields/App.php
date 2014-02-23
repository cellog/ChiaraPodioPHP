<?php
namespace Chiara\PodioItem\Diff\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Collection;
class App extends Field
{
    protected $added = false, $deleted;
    function getValue()
    {
        return new Collection($this, $this->info['to'], 'Chiara\\PodioItem\\Values\\App');
    }

    function getOldValue()
    {
        return new Collection($this, $this->info['from'], 'Chiara\\PodioItem\\Values\\App');
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

    protected function calculateChanges()
    {
        if (false !== $this->added) return;
        $this->added = $this->deleted = array();
        $tempa = array();
        foreach ($this->info['to'] as $i => $value) {
            $tempa[$value['item_id']] = $i;
        }
        $tempd = array();
        foreach ($this->info['from'] as $i => $value) {
            $tempd[$value['item_id']] = $i;
        }
        foreach ($tempd as $id => $i) {
            if (!isset($tempa[$id])) {
                $this->deleted[] = $this->info['from'][$i];
            }
        }
        foreach ($tempa as $id => $i) {
            if (!isset($tempd[$id])) {
                $this->added[] = $this->info['to'][$i];
            }
        }
    }

    function __get($var)
    {
        if ($var == 'referenceable_types') return $this->to['info']['config']['settings']['referenceable_types'];
        if ($var == 'deleted') {
            $this->calculateChanges();
            return new Collection($this, $this->deleted, 'Chiara\\PodioItem\\Values\\App');
        }
        if ($var == 'added') {
            $this->calculateChanges();
            return new Collection($this, $this->added, 'Chiara\\PodioItem\\Values\\App');
        }
        return parent::__get($var);
    }
}