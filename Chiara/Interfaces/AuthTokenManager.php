<?php
namespace Chiara\Interfaces;
interface AuthTokenManager
{
    function getToken($appid);
    function saveToken($appid, $token);
    function getAPIClient();
    function saveAPIClient($client, $token);
    function mapAppToClass($appid, $classname);
    function getAppClass($appid, $defaultClass = 'Chiara\PodioItem');
}