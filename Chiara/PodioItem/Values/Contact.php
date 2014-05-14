<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioContact;
class Contact extends Reference
{
    protected $mycontact;
    function retrieveReference()
    {
        if ($this->mycontact) return $this->mycontact;
        return $this->mycontact = new PodioContact($this->info['value']);
    }

    function extendedGet($var)
    {
        return $this->retrieveReference()->__get($var);
    }

    function getIndices()
    {
        return array(
            $this->info['value']['profile_id']
        );
    }

    function isSpaceContact()
    {
        return $this->info['value']['type'] == 'space';
    }

    function saveValue()
    {
        return $this->info['value']['profile_id'];
    }
}
