<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioContact;
class Contact extends Reference
{
    function retrieveReference()
    {
        return new PodioContact($this->info['value'], 'force');
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
