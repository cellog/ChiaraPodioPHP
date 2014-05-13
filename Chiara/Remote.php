<?php
namespace Chiara;
use Podio, Chiara\AuthManager as Auth;
class Remote
{
    static public $remote;

    function mergeOptions($attributes, $options)
    {
        if (isset($options['hook'])) {
            $attributes['hook'] = $options['hook'] ? '1' : '0';
        }
        if (isset($options['silent'])) {
            $attributes['silent'] = $options['silent'] ? '1' : '0';
        }
        if (isset($options['alert_invite'])) {
            $attributes['alert_invite'] = $options['alert_invite'] ? '1' : '0';
        }
        if (isset($options['fields'])) {
            $attributes['fields'] = $options['fields'];
        }
        return $attributes;
    }

    function get($url, $attributes = array(), $options = array())
    {
        return Podio::get($url, $this->mergeOptions($attributes, $options));
    }

    function post($url, $attributes = array(), $options = array())
    {
        return Podio::post(Podio::url_with_options($url, $this->mergeOptions(array(), $options)), $attributes);
    }

    function put($url, $attributes = array(), $options = array())
    {
        return Podio::put(Podio::url_with_options($url, $this->mergeOptions(array(), $options)), $attributes);
    }

    function delete($url, $attributes = array())
    {
        return Podio::delete($url, $attributes);
    }

    function authenticate_with_app($app_id, $app_token)
    {
        return Podio::authenticate_with_app($app_id, $app_token);
    }

    function authenticate_with_password($username, $password)
    {
        return Podio::authenticate('password', array('username' => $username, 'password' => $password));
    }

    function authenticate($grant_type, $attributes)
    {
        return Podio::authenticate($grant_type, $attributes);
    }

    function setup($client_id, $client_secret, $options = array('session_manager' => 'PodioSession', 'curl_options' => array()))
    {
        return Podio::setup($client_id, $client_secret, $options);
    }

    static function search($context, $match, $limit = 20, $offset = 0)
    {
        return self::$remote->searchengine($context, $match, $limit, $offset);
    }
    
    protected function searchengine($context, $match, $limit = 20, $offset = 0)
    {
        if ($context instanceof PodioApp) {
            Auth::prepareRemote($context->id);
            $api = 'app/' . $context->id . '/';
        } else if ($context instanceof PodioWorkspace) {
            Auth::verifyNonApp('workspace search');
            $api = 'space/' . $context->id . '/';
        } else if ($context instanceof PodioOrganization) {
            Auth::verifyNonApp('organization search');
            $api = 'org/' . $context->id . '/';
        } else {
            Auth::verifyNonApp('global search');
            $api = '';
        }
        $options = array('query' => (string) $match);
        if ($limit != 20) {
            $options['limit'] = $limit;
        }
        if ($offset) {
            $options['offset'] = $offset;
        }
        $ret = self::$remote->post('/search/' . $api,
                                            $options)->json_body();
        // TODO: implement an iterator for search results
        return new Iterators\SearchIterator($ret);
    }
}
Remote::$remote = new Remote;