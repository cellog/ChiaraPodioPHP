<?php
namespace Chiara\Iterators\PodioItemFilterIterator\Fields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
class Date extends FromTo
{
    protected $relativefrom = false;
    protected $relativeto = false;
    protected $settingfrom = false;
    protected $settingto = false;
    function __construct(App $app, View $view, $info)
    {
        parent::__construct($app, $view, $info);
    }

    /**
     * Set the from value to a relative date
     */
    function past($value, $units = 'd', $round = false)
    {
        $this->relativefrom = true;
        $this->settingfrom = true;
        $this->from($this->relative($value, $units, $round));
        $this->settingfrom = false;
        return $this;
    }

    /**
     * Set the to value to a relative date
     */
    function future($value, $units = 'd', $round = false)
    {
        $this->relativeto = true;
        $this->settingto = true;
        $this->to($this->relative($value, $units, $round));
        $this->settingto = false;
        return $this;
    }

    function rounded()
    {
        if ($this->relativefrom) {
            if (isset($this->info['from']) && !strpos($this->info['from'], 'r')) {
                $this->info['from'] .= 'r';
            }
        }
        if ($this->relativeto) {
            if (isset($this->info['to']) && !strpos($this->info['to'], 'r')) {
                $this->info['to'] .= 'r';
            }
        }
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
        if ($this->relativefrom) {
            if (isset($this->info['from'])) {
                $this->info['from'] = preg_replace('/([-+]\d+)[dwmy](r)?/', '$1' . $units . '$2', $this->info['from']);
            }
        }
        if ($this->relativeto) {
            if (isset($this->info['to'])) {
                $this->info['to'] = preg_replace('/([-+]\d+)[dwmy](r)?/', '$1' . $units . '$2', $this->info['to']);
            }
        }
    }

    protected function relative($value, $units, $round)
    {
        $value = (int) $value;
        if ($units !== 'd' || $units !== 'w' || $units !== 'm' || $units !== 'y') {
            throw new \Exception('Relative units must by d, w, m or y (days, weeks, months or years)');
        }
        $round = $round ? 'r' : '';
        $value = (($value >= 0) ? '+' . $value : '-' . $value);
        return $value . $units . $round;
    }

    function validate($value)
    {
        if ($this->settingfrom || $this->settingto) {
            if (!preg_match('/^[-+]\d+[dwmy]r?$/', $value)) {
                throw new \Exception('Invalid relative date value "' . $value . '"');
            }
            return $value;
        }
        try {
            new \DateTime($value);
            return $value;
        } catch (\Exception $e) {
            throw new \Exception('Invalid date "' . $value . '"');
        }
    }
}
