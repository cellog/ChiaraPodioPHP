<?php
namespace Chiara;
use Podio;
class AuthManager
{
    const USER = 1;
    const APP = 2;
    static protected $tokenmanager;
    static protected $authmode = self::USER;
    static protected $currentapp = null;
    static function setTokenManager(AuthManager\TokenInterface $manager)
    {
        self::$tokenmanager = $manager;
        $clientinfo = $manager->getAPIClient();
        Podio::setup($clientinfo['client'], $clientinfo['token']);
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
        if (self::$currentapp == $appid) return; // we are already authenticated as this app
        Podio::authenticate_with_app($appid, self::$tokenmanager->getToken($appid));
    }

    static function verifyNonApp($thing)
    {
        if (self::$authmode == self::APP) {
            throw new \Exception('Cannot access ' . $thing . ' in app authentication mode');
        }
    }
}
