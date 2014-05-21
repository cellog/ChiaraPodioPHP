<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;

class AuthClient extends Auth
{
    function app($client_id)
    {
        $this->filterinfo = array(
            'type' => 'app',
            'id' => $client_id
        );
        $this->saveFilter();
    }

    function me()
    {
        throw new \Exception('Logic error: ' . $this->info['field_id'] . ' is ' .
                             'an auth client pseudofield, there is no concept ' .
                             'of "me"');
    }
}
