<?php
namespace Chiara\Iterators\PodioItemFilterIterator\PseudoFields;
use Chiara\PodioApp as App, Chiara\Iterators\PodioItemFilterIterator as Filter,
    Chiara\Iterators\PodioItemFilterIterator\Field;
class Auth extends Field
{
    function __construct(App $app, Filter $filter, $name)
    {
        parent::__construct($app, $filter, array('field_id' => $name));
    }

    protected function addIdentity($id, $type)
    {
        foreach ($this->filterinfo as $i => $info) {
            if ($info['type'] != $type) {
                continue;
            }
            if ($id == $info['id']) {
                $this->filterinfo[$i] = array('type' => $type, 'id' => $id);
                return;
            }
        }
        $this->filterinfo[] = array('type' => $type, 'id' => $id);
    }

    function user($user_id)
    {
        if (is_object($user_id)) {
            $user_id = $user_id->user_id;
        }
        if (is_array($user_id)) {
            $user_id = $user_id['user_id'];
        }
        $user_id = (int) $user_id;
        $this->addIdentity($user_id, 'user');
        $this->saveFilter();
        return $this;
    }

    function app($app_id)
    {
        if (is_object($app_id)) {
            $app_id = $app_id->id;
        }
        if (is_array($app_id)) {
            $app_id = $app_id['app_id'];
        }
        $app_id = (int) $app_id;
        $this->addIdentity($user_id, 'app');
        $this->saveFilter();
        return $this;
    }

    function me()
    {
        $this->filterinfo = array(
            'type' => 'user',
            'id' => 0
        );
        $this->saveFilter();
        return $this;
    }
}
