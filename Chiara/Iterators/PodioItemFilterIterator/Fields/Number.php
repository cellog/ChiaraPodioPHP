<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Number extends FromTo
{
    protected $userange = false;
    function verifyPossible()
    {
        $this->userange = true;
        return $this;
    }

    function validate($value)
    {
        if ($this->userange) {
            $range = $this->app->fields[$this->info['field_id']]->range;
            if ($value < $range['min'] || $value > $range['max']) {
                throw new \Exception('Cannot use value "' . $value . '", it is' .
                                     ' not within the range of possible field ' .
                                     'values "' . $range['min'] . '"->"' . $range['max'] . '"');
            }
        }
        return (float) $value;
    }
}
