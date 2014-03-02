--TEST--
HookServer->removeHook (through PodioApp->removeHook)
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote, Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Remote::$remote->expectRequest('GET', '/hook/app/5/', json_encode(array(array('hook_id' => 4, 'url' => 'http://example.com/hook.php/',
                                                                               'type' => 'item.update'),
                                                                         array('hook_id' => 10, 'url' => 'http://example.com/hook.php/',
                                                                               'type' => 'item.create'))),
                               array());
Remote::$remote->expectRequest('DELETE', '/hook/10', '',
                               array());
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$tokens->saveToken(5, 123);
Auth::setTokenManager($tokens);
$item = new Chiara\PodioApp(array('app_id' => 5), false);
Hook::$hookserver->setBaseUrl('http://example.com/hook.php');
$item->removeHook('item.create');

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
    0 => 'get',
    1 => 
    array (
      0 => '/hook/app/5/',
      1 => 
      array (
      ),
      2 => 
      array (
      ),
    ),
  ),
  2 => 
  array (
    0 => 'delete',
    1 => 
    array (
      0 => '/hook/10',
      1 => 
      array (
      ),
    ),
  ),
), Chiara\Remote::$remote->queries, 'queries');
echo "done\n";
?>
--EXPECT--
done
