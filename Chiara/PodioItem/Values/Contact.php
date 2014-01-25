<?php
namespace Chiara\PodioItem\Values;
use Chiara\PodioContact;
class Contact extends Reference
{
    function retrieveReference()
    {
        return new PodioContact($this->info, 'force');
    }

    function getIndices()
    {
        return array(
            $this->info['profile_id']
        );
    }

    function isSpaceContact()
    {
        return $this->info['type'] == 'space';
    }
}
