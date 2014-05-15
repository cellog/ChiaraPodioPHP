<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View;
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
