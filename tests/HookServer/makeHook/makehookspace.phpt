--TEST--
HookServer->makeHook (through PodioApp->createHook)
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote, Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Remote::$remote->expectRequest('POST', '/hook/space/5/', json_encode(array('hook_id' => 10)),
                               array('url' => 'http://example.com/hook.php/', 'type' => 'app.create'));
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$tokens->saveToken(5, 123);
Auth::setTokenManager($tokens);
$item = new Chiara\PodioWorkspace(json_decode(file_get_contents(__DIR__ . '/space.json'), 1), false);
Hook::$hookserver->setBaseUrl('http://example.com/hook.php');
$test->assertEquals(10, $item->hook['app.create']->create(), 'return of app.create hook creation');

$test->assertEquals(array (
  0 => 
  array (
    'setup' => 
    array (
      0 => 5,
      1 => 6,
      2 => 
      array (
        'session_manager' => 'PodioSession',
        'curl_options' => 
        array (
        ),
      ),
    ),
  ),
  1 => 
  array (
    0 => 'post',
    1 => 
    array (
      0 => '/hook/space/5/',
      1 => 
      array (
        'url' => 'http://example.com/hook.php/',
        'type' => 'app.create',
      ),
      2 => 
      array (
      ),
    ),
  ),
), Chiara\Remote::$remote->queries, 'queries');
echo "done\n";
?>
--EXPECT--
done
