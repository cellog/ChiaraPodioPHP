<?php
namespace Chiara;
use Chiara\Interfaces\Router, Chiara\Remote, Chiara\AuthManager as Auth;
class HookServer implements Router
{
    static $hookserver;
    protected $handlers = array();
    protected $router;
    protected $baseurl;
    protected $input;
    protected $server;

    function __construct($baseurl = '', $post = null, $server = null)
    {
        $this->baseurl = $baseurl;
        if ($post) {
            $this->input = $post;
        } else {
            $this->input = $_POST;
        }
        if ($server) {
            $this->server = $server;
        } else {
            $this->server = $_SERVER;
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
        if (isset($this->server['PATH_INFO'])) {
            $info = explode('/', $this->server['PATH_INFO']);
            array_shift($info);
            $action = array_shift($info);
            $params = $info;
            $test = $action;
            if (isset($this->handlers[$this->input['type']])) {
                $t = $this->handlers[$this->input['type']];
                if (is_array($t)) {
                    if (isset($t[0])) {
                        // this is the handler for generic, no specific action
                        unset($t[0]);
                    }
                    if (count($t)) {
                        $actual = array();
                        array_unshift($params, $action);
                        do {
                            while (count($params)) {
                                $test = implode('/', $params);
                                if (isset($t[$test])) {
                                    $action = $test;
                                    $params = $actual;
                                    break 2;
                                }
                                array_unshift($actual, array_pop($params));
                            }
                            $params = $info; // not found
                        } while (false);
                    } else {
                        array_unshift($params, $action);
                        $action = 0;
                    }
                }
            }
            return array('action' => $action, 'params' => $params);
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
        list ($url, $id, $ref_type) = $this->getIdUrl($action, $podioaction, $context, $id);
        if (!$id) {
            throw new \Exception('Id must be explicitly specified to create a hook');
        }
        $ret = Remote::$remote->post('/hook/' . $ref_type . '/' . $id . '/', array(
            'url' => $url,
            'type' => $podioaction
        ))->json_body();
        return $ret['hook_id'];
    }

    protected function getIdUrl($action, $podioaction, $context, $id)
    {
        $url = $this->getHookUrl($action);
        $this->validHook($podioaction);
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

    function removeHook($context, $action, $podioaction, $id = null)
    {
        list ($url, $id, $ref_type) = $this->getIdUrl($action, $podioaction, $context, $id);
        if (!$id) {
            throw new \Exception('Id must be explicitly specified to remove a hook');
        }
        $hooks = Remote::$remote->get('/hook/' . $ref_type . '/' . $id . '/')->json_body();
        foreach ($hooks as $hook) {
            if ($hook['url'] == $url && $hook['type'] == $podioaction) {
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
        if (!isset($this->handlers[$podioaction])) {
            $this->handlers[$podioaction] = array();
        }
        if (!$action) {
            $action = 0;
        }
        $this->handlers[$podioaction][$action] = $handler;
    }

    function unregisterHandler($podioaction, $action)
    {
        if (!$this->handlerExists($podioaction, $action)) return;
        if (!$action) {
            $action = 0;
        }
        unset($this->handlers[$podioaction][$action]);
        if (!count($this->handlers[$podioaction])) {
            unset($this->handlers[$podioaction]);
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
        if (!$action) $action = 0;
        return $this->handlers[$podioaction][$action];
    }

    function handlerExists($podioaction, $action = 0)
    {
        $this->validHook($podioaction);
        if (!$action) {
            $action = 0;
        }
        return isset($this->handlers[$podioaction]) && isset($this->handlers[$podioaction][$action]);
    }

    function perform()
    {
        Auth::beginHook(); // ensure that hook = false is passed in options
        $info = $this->router->route();
        if (isset($this->handlers[$this->input['type']])) {
            if (isset($info['action'])) {
                if (isset($this->handlers[$this->input['type']][$info['action']])) {
                    $action = $this->handlers[$this->input['type']][$info['action']];
                    return call_user_func($action, $this->input, $info['params']);
                }
            }
            if (isset($this->handlers[$this->input['type']][0]) && is_callable($this->handlers[$this->input['type']][0])) {
                return call_user_func($this->handlers[$this->input['type']][0], $this->input, $info['params']);
            }
        }
        if ($info['action']) {
            throw new \Exception('Unhandled route action for "' . $this->input['type'] . '", action "' . $info['action'] . '"');
        }
        throw new \Exception('Unhandled route action for "' . $this->input['type'] . '"');
    }
}
HookServer::$hookserver = new HookServer;
