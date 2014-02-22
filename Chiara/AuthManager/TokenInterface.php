<?php
namespace Chiara\AuthManager;
interface TokenInterface
{
    function getToken($appid);
    function saveToken($appid, $token);
    function getAPIClient();
    function saveAPIClient($client, $token);
    function mapAppToClass($appid, $classname);
    function getAppClass($appid, $defaultClass = 'Chiara\PodioItem');
}