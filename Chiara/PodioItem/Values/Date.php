<?php
namespace Chiara\PodioItem\Values;
class Date implements \IteratorAggregate
{
    protected $info;
    function __construct($info = null)
    {
        $this->info = $info;
    }

    /**
     * @return null|DatePeriod a DatePeriod, the start and end times can be retrieved using a foreach loop
     */
    function getValue()
    {
        if (!$this->info['start']) {
            $a = new \DateTime;
            return new \DatePeriod($a, new \DateInterval, clone $a);
        }
        if (isset($this->info['start'])) {
            $a = \DateTime::createFromFormat('Y-m-d H:i:s', $this->info['start']);
            $b = clone $a;
            $b->modify("+1 second");
            $interval = $a->diff($b);
        }
        if (isset($this->info['end'])) {
            $b = \DateTime::createFromFormat('Y-m-d H:i:s', $this->info['end']);
            $interval = $a->diff($b);
            $b->modify("+1 days");
        }
        return new \DatePeriod($a, $interval, $b);
    }

    function getDuration()
    {
        if (!$this->info['end']) {
            return new \DateInterval("PT0S");
        }
        return \DateTime::createFromFormat('Y-m-d H:i:s', $this->info['start'])
            ->diff(\DateTime::createFromFormat('Y-m-d H:i:s', $this->info['end']));
    }

    function getIterator()
    {
        return $this->getValue();
    }

    function __get($var)
    {
        return $this->info[$var];
    }

    function __set($var, $value)
    {
        throw new \Exception('do not set values this way, set it directly with the fields array of the podio item');
    }

    function __toString()
    {
        if (!isset($this->info['start'])) {
            return '(empty)';
        }
        if (isset($this->info['end'])) {
            return $this->info['start'] . ' => ' . $this->info['end'];
        }
        return $this->info['start'];
    }
}