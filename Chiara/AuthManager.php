<?php
namespace Chiara;
use Podio;
class AuthManager
{
    const USER = 1;
    const APP = 2;
    static protected $tokenmanager;
    static protected $authmode = self::USER;
    static function setTokenManager(AuthManager\TokenInterface $manager, $client, $token)
    {
        self::$tokenmanager = $manager;
        Podio::setup($client, $token);
    }

    static function setAuthMode($mode)
    {
        self::$authmode = $mode;
    }

    static function prepareRemote($appid)
    {
        if (self::$authmode == self::USER) {
            return true;
        }
        Podio::authenticate_with_app($appid, self::$tokenmanager->getToken($appid));
    }
}
