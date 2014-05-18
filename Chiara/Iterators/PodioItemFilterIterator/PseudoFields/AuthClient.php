<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;

class AuthClient extends Auth
{
    function app($app_id)
    {
        if (is_object($app_id)) {
            $app_id = $app_id->id;
        }
        if (is_array($app_id)) {
            $app_id = $app_id['app_id'];
        }
        $app_id = (int) $app_id;
        $this->filterinfo = array(
            'type' => 'app',
            'id' => $app_id
        );
        $this->saveFilter();
    }
}
