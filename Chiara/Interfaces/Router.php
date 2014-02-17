<?php
namespace Chiara\Interfaces;
interface Router
{
    function registerHandler($podioaction, $action, $handler);
    function unregisterHandler($podioaction, $action);
    function handlerExists($podioaction, $action = null);
    function getHandler($podioaction, $action);
    function getHookUrl($action);
    function route();
}