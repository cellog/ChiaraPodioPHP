<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\IteratableField, Chiara\PodioItem\Values\Collection;
class Embed extends IteratableField
{
    function getValue()
    {
        return new Collection($this, $this->info['values'], 'Chiara\\PodioItem\\Values\\Embed');
    }

    function getSaveValue()
    {
        $value = $this->getValue();
        $ret = array();
        foreach ($value as $v) {
            if ($v->url) {
                $ret[] = array('url' => $v->url);
            } else {
                $ret[] = array('embed' => $v->embed_id, 'file' => $v->file_id);
            }
        }
        return $ret;
    }
}