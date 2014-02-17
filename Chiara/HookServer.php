<?php
namespace Chiara;
use Chiara\Interfaces\Router, Chiara\Remote, Chiara\AuthManager as Auth;
class HookServer implements Router
{
    static $hookserver;
    protected $handlers = array();
    protected $router;
    protected $baseurl;

    function __construct($post = null, $baseurl = '')
    {
        $this->baseurl = $baseurl;
        if ($post) {
            $this->input = $post;
        } else {
            $this->input = $_POST;
        }
        $this->registerRouter($this);
        $this->handlers['hook.verify'] = array($this, 'hookVerify');
    }

    function setBaseUrl($url)
    {
        $this->baseurl = $url;
    }

    function registerRouter(Router $router)
    {
        $this->router = $router;
    }

    function route()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            $info = explode('/', $_SERVER['PATH_INFO']);
            array_shift($info);
            $action = array_shift($info);
            return array('action' => $action, 'params' => $info);
        }
    }

    function hookVerify()
    {
        Remote::$remote->post('/hook/' . $this->input['hook_id'] . '/verify/validate', array('code' => $this->input['code']));
    }

    function validHook($podioaction)
    {
        if (!in_array($podioaction, array('item.create', 'item.update', 'item.delete', 'comment.create', 'comment.delete', 'file.change',
                                          'app.update', 'app.delete', 'app.create', 'task.create', 'task.update', 'task.delete', 'member.add',
                                          'member.remove', 'hook.verify'))) {
            throw new \Exception('Invalid podio hook action: "' . $podioaction . '"');
        }
    }

    /**
     * @param string|Chiara\PodioApp|Chiara\PodioApp\Field|Chiara\PodioWorkspace
     *                      if a string, "app", "app_field", or "space"
     * @param string the base action.  Url is auto-calculated from the base url
     * @param string one of the hook types (item.create, etc.)
     * @param int id of the context.  Must be the value of either app_id, field_id,
     *            or space_id.  This is only required if $context is a string
     */
    function makeHook($context, $action, $podioaction, $id = null)
    {
        list ($url, $id, $ref_type) = $this->getIdUrl($action, $podioaction, $context);
        if (!$id) {
            throw new \Exception('Id must be explicitly specified to create a hook');
        }
        $ret = Remote::$remote->post('/hook/' . $ref_type . '/' . $id . '/', array(
            'url' => $url,
            'type' => $podioaction
        ))->json_body();
        return $ret['hook_id'];
    }

    protected function getIdUrl()
    {
        $url = $this->getHookUrl($action);
        $this->validHook($hooktype);
        if ($context instanceof PodioApp) {
            $id = $context->id;
            $ref_type = 'app';
        } elseif ($context instanceof PodioApp\Field) {
            $id = $context->id;
            $ref_type = 'app_field';
        } elseif ($context instanceof PodioWorkspace) {
            $id = $context->id;
            $ref_type = 'space';
        } elseif (is_string($context)) {
            if ($context !== 'app' || $context !== 'app_field' || $context !== 'space') {
                throw new \Exception('Context must be one of app, app_field or space');
            }
        }
        return array($url, $id, $ref_type);
    }

    function removeHook($context, $action, $hooktype, $id = null)
    {
        list ($url, $id, $ref_type) = $this->getIdUrl($action, $hooktype, $context);
        if (!$id) {
            throw new \Exception('Id must be explicitly specified to remove a hook');
        }
        $hooks = Remote::$remote->get('/hook/' . $hooktype . '/' . $id)->json_body();
        foreach ($hooks as $hook) {
            if ($hook['url'] == $url) {
                Remote::$remote->delete('/hook/' . $hook['hook_id']);
                return;
            }
        }
    }

    function getHookUrl($action)
    {
        return $this->baseurl . '/' . $action;
    }

    function registerHandler($podioaction, $action, $handler)
    {
        $this->validHook($podioaction);
        if (!is_callable($handler)) {
            throw new \Exception('Handler must be callable for podio action "' . $podioaction . '"');
        }
        if (!$action) {
            $this->handlers[$podioaction] = $handler;
        }
        $this->handlers[$podioaction][$action] = $handler;
    }

    function unregisterHandler($podioaction, $action)
    {
        if (!$this->handlerExists($podioaction, $action)) return;
        if ($action) {
            unset($this->handlers[$podioaction][$action]);
            if (!count($this->handlers[$podioaction])) {
                unset($this->handlers[$podioaction]);
            }
        } else {
            $keys = array_keys($this->handlers[$podioaction]);
            // ensure that no other handlers exist before unsetting
            foreach ($keys as $key) {
                if (!is_int($key)) {
                    throw new \Exception('Cannot unset handler for podio action "' . $podioaction .
                                         '", handlers for specific actions exist');
                }
            }
        }
    }

    function getHandler($podioaction, $action)
    {
        if (!$this->handlerExists($podioaction, $action)) {
            if ($action) {
                throw new \Exception('Handler for podio action "' . $podioaction . '" action "' . $action . '" does not exist');
            }
            throw new \Exception('Handler for podio action "' . $podioaction . '" does not exist');
        }
        if ($action) {
            return $this->handlers[$podioaction][$action];
        }
        return $this->handlers[$podioaction];
    }

    function handlerExists($podioaction, $action = null)
    {
        $this->validHook($podioaction);
        if ($action) {
            return isset($this->handlers[$podioaction]) && isset($this->handlers[$action]);
        }
        return isset($this->handlers[$podioaction]);
    }

    function perform($userauth = false)
    {
        if ($userauth) {
            Auth::prepareRemote(Auth::USER);
        } else {
            Auth::prepareRemote(Auth::APP);
        }
        $info = $this->router->route();
        if (isset($this->handlers[$this->input['type']])) {
            if (is_callable($this->handlers[$this->input['type']])) {
                return call_user_func_array($this->handlers[$this->input['type']], $this->input, $info['params']);
            }
            if (isset($this->handlers[$this->input['type']][$info['action']])) {
                $action = $this->handlers[$this->input['type']][$info['action']];
                return call_user_func_array($action, $this->input, $info['params']);
            }
        }
        if ($info['action']) {
            throw new \Exception('Unhandled route action for "' . $this->input['type'] . '", action "' . $info['action'] . '"');
        }
        throw new \Exception('Unhandled route action for "' . $this->input['type'] . '"');
    }
}
HookServer::$hookserver = new HookServer;
