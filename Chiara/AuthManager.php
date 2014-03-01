<?php
namespace Chiara;
use Podio, Chiara\Remote;
class AuthManager
{
    const USER = 1;
    const APP = 2;
    static protected $tokenmanager;
    static protected $authmode = self::USER;
    static protected $currentapp = null;
    static protected $ishook = false;
    static function setTokenManager(AuthManager\TokenInterface $manager)
    {
        self::$tokenmanager = $manager;
        $clientinfo = $manager->getAPIClient();
        Remote::$remote->setup($clientinfo['client'], $clientinfo['token']);
    }

    static function getOptions($options)
    {
        if (!isset($options['hook'])) {
            $options['hook'] = false;
        }
        return $options;
    }

    static function beginHook()
    {
        self::$ishook = true;
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
        Remote::$remote->authenticate_with_app($appid, self::$tokenmanager->getToken($appid));
    }

    static function attemptPasswordLogin($logoutvariable = false)
    {
        if (self::$authmode == self::APP) {
            return;
        }
        if (!Podio::is_authenticated()) {
            $username = readline("Please enter your Podio username: ");
            $password = readline("Please enter your Podio password: ");
            Remote::$remote->authenticate_with_password($username, $password);
        }
    }

    static function attemptServerLogin($redirectUri, $logoutvariable = false, $code = null)
    {
        if (self::$authmode == self::APP) {
            return;
        }
        if (!$redirectUri) {
            throw new \Exception('Cannot authenticate unless redirect URI is specific explicitly');
        }
        if ($code) {
            self::authenticateServer($redirectUri, $code);
            $logoutvariable = false;
        }
        if ($logoutvariable || !Podio::$oauth->access_token) {
            $client = self::$tokenmanager->getAPIClient();
            header('Location: https://podio.com/oauth/authorize?client_id=' . $client['client'] . '&redirect_uri=' .
                   urlencode($redirectUri));
            exit;
        }
    }

    static function authenticateServer($redirectUri, $code)
    {
        if ($code) {
            Remote::$remote->authenticate('authorization_code', array('code' => $code,
                                                            'redirect_uri' => $redirectUri));
            return true;
        }
        return false;
    }

    static function verifyNonApp($thing)
    {
        if (self::$authmode == self::APP) {
            throw new \Exception('Cannot access ' . $thing . ' in app authentication mode');
        }
    }

    static function getTokenManager()
    {
        return self::$tokenmanager;
    }
}
