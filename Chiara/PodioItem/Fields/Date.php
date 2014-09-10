<?php
namespace Chiara\PodioItem\Fields;
use Chiara\PodioItem\Field, Chiara\PodioItem\Values\Date as Value;
class Date extends Field
{
    function getValue()
    {
        return new Value($this->info['values'][0]);
    }

    function getSaveValue()
    {
        $ret = array();
        if (isset($this->info['values'][0]['start'])) {
            $ret['start'] = $this->info['values'][0]['start'];
        }
        if (isset($this->info['values'][0]['end'])) {
            $ret['end'] = $this->info['values'][0]['end'];
        }
        return $ret;
    }

    function __get($var)
    {
        if ($var === 'duration') {
            return $this->getValue()->getDuration();
        }
        return parent::__get($var);
    }

    function __set($var, $value)
    {
        if ($var === 'duration') {
            $t = strtotime($this->info['values'][0]['start']);
            $t += $value;
            $this->info['values'][0]['end'] = date('Y-m-d H:i:s', $t);
        } else if ($var === 'start' || $var === 'end') {
            if (is_numeric($value)) {
                $result = date('Y-m-d H:i:s', $value);
            } else if ($value instanceof \DateTime) {
                $result = $value->format('Y-m-d H:i:s');
            } else if (is_string($value)) {
                $result = date('Y-m-d H:i:s', strtotime($value));
            }
            $this->info['values'][0][$var] = $result;
        }
        $this->parent->setFieldValue($this->info['field_id'], $this->info['values']);
    }
}