<?php
namespace Chiara\Hook;
use Chiara\PodioApp as App, Chiara\PodioApp\Field as Field, Chiara\PodioWorkspace as Space,
    Chiara\HookServer as Server;
class Manager implements \ArrayAccess
{
    protected $allowedContexts;
    protected $context;
    protected $action;
    protected $podioaction;
    function __construct($context, $action = 0, $podioaction = null)
    {
        $this->context = $context;
        $this->action = $action;
        $this->podioaction = $podioaction;
        if ($context instanceof App) {
            $this->allowedContexts = array(
                'item.create', 'item.update', 'item.delete',
                'comment.create', 'comment.delete',
                'file.change',
                'app.update',
                'app.delete'
            );
        } elseif ($context instanceof Field) {
            $this->allowedContexts = array(
                'item.create', 'item.update', 'item.delete'
            );
        } elseif ($context instanceof Space) {
            $this->allowedContexts = array(
                'app.create',
                'task.create', 'task.update', 'task.delete',
                'member.add', 'member.remove'
            );
        } else {
            throw new \Exception('Invalid context, must be an app, field or workspace object');
        }
    }

    function offsetExists($offset)
    {
        return Server::$hookserver->handlerExists($offset, $this->action);
    }

    function offsetGet($offset)
    {
        if (isset($this->podioaction)) {
            throw new \Exception('Hook action already requested "' . $this->podioaction . '"');
        }
        return new static($this->context, $this->action, $offset);
    }

    function offsetSet($offset, $handler)
    {
        Server::$hookserver->registerHandler($offset, $this->action, $handler);
    }

    function offsetUnset($offset)
    {
        Server::$hookserver->unregisterHandler($offset, $this->action);
    }

    function create()
    {
        if (!isset($this->podioaction)) {
            throw new \Exception('Hook action must be specified before creating a hook');
        }
        return Server::$hookserver->makeHook($this->context, $this->action, $this->podioaction);
    }

    function remove()
    {
        if (!isset($this->podioaction)) {
            throw new \Exception('Hook action must be specified before removing a hook');
        }
        return Server::$hookserver->removeHook($this->context, $this->action, $this->podioaction);
    }

    function __get($var)
    {
        $action = $this->action;
        if ($action) {
            $action .= '/';
        } else {
            $action = '';
        }
        return new static($this->context, $action . $var);
    }
}
