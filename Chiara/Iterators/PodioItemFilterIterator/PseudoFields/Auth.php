<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\PodioView as View,
    Chiara\Iterators\PodioItemFilterIterator\Field;
class Auth extends Field
{
    function __construct(App $app, View $view, $name)
    {
        parent::__construct($app, $view, $name);
        $this->info = array('field_id' => $name);
    }

    function user($user_id)
    {
        if (is_object($user_id)) {
            $user_id = $user_id->id;
        }
        if (is_array($user_id)) {
            $user_id = $user_id['user_id'];
        }
        $user_id = (int) $user_id;
        $this->filterinfo = array(
            'type' => 'user',
            'id' => $user_id
        );
        $this->saveFilter();
    }

    function me()
    {
        $this->filterinfo = array(
            'type' => 'user',
            'id' => 0
        );
        $this->saveFilter();
    }
}
