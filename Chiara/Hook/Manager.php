<?php
namespace Chiara\Hook;
use Chiara\PodioApp as App, Chiara\PodioApp\Field as Field, Chiara\PodioWorkspace as Space,
    Chiara\HookServer as Server;
class Manager implements \ArrayAccess
{
    protected $allowedContexts;
    protected $context;
    protected $action;
    function __construct($context, $action = null)
    {
        $this->context = $context;
        $this->action = $action;
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
        return Server::$hookserver->getHandler($offset, $this->action);
    }

    function offsetSet($offset, $handler)
    {
        Server::$hookserver->registerHandler($offset, $this->action, $handler);
    }

    function offsetUnset($offset)
    {
        Server::$hookserver->unregisterHandler($offset, $this->action);
    }

    function __get($var)
    {
        $action = $this->action;
        if ($action) {
            $action .= '/';
        }
        return new static($this->context, $action . $var);
    }
}
