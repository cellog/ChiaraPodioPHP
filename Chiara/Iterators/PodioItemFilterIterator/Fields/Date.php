<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;

class Date extends FromTo
{
    protected $setting = false;
    protected $mostrecent = false;
    /**
     * Set the from value to a relative date
     */
    function past($value, $units = 'd', $round = true)
    {
        $this->setting = 'from';
        $this->from($this->relative(-$value, $units, $round));
        $this->setting = 'from';
        $this->mostrecent = 'past';
        if (!$this->filterinfo['to']) {
            $this->filterinfo['to'] = '+0dr';
        }
        $this->saveFilter();
        return $this;
    }

    /**
     * Set the to value to a relative date
     */
    function future($value, $units = 'd', $round = true)
    {
        $this->setting = 'to';
        $this->to($this->relative($value, $units, $round));
        $this->setting = 'to';
        $this->mostrecent = 'future';
        if (!$this->filterinfo['from']) {
            $this->filterinfo['from'] = '+0dr';
        }
        $this->saveFilter();
        return $this;
    }

    function notRounded()
    {
        $var = ($this->mostrecent === 'past' ? 'from' : ($this->mostrecent === 'future' ? 'to' : false));
        if (!$var || !isset($this->filterinfo[$var])) {
            return $this;
        }
        $this->filterinfo[$var] = str_replace('r', '', $this->filterinfo[$var]);
        $this->saveFilter();
        return $this;
    }

    function days()
    {
        $this->replace('d');
        return $this;
    }

    function weeks()
    {
        $this->replace('w');
        return $this;
    }

    function months()
    {
        $this->replace('m');
        return $this;
    }

    function years()
    {
        $this->replace('y');
        return $this;
    }

    protected function replace($units)
    {
        $var = ($this->mostrecent === 'past' ? 'from' : ($this->mostrecent === 'future' ? 'to' : false));
        if (!$var || !isset($this->filterinfo[$var])) {
            return $this;
        }
        $this->filterinfo[$var] = preg_replace('/([-+]\d+)[dwmy](r?)/', '\\1' . $units . '\\2', $this->filterinfo[$var]);
        $this->saveFilter();
    }

    protected function relative($value, $units, $round)
    {
        $value = (int) $value;
        if ($units !== 'd' && $units !== 'w' && $units !== 'm' && $units !== 'y') {
            throw new \Exception('Relative units must by d, w, m or y (days, weeks, months or years)');
        }
        $round = $round ? 'r' : '';
        $value = (($value >= 0) ? '+' . $value : $value);
        return $value . $units . $round;
    }

    function validate($value)
    {
        if (false !== $this->setting) {
            if (!preg_match('/^[-+]\d+[dwmy]r?$/', $value)) {
                throw new \Exception('Invalid relative date value "' . $value . '"');
            }
            return $value;
        }
        try {
            $value = new \DateTime($value);
            return $value->format('Y-m-d');
        } catch (\Exception $e) {
            throw new \Exception('Invalid date "' . $value . '"');
        }
    }
}
