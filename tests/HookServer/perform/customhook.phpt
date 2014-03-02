--TEST--
HookServer->perform registered handler
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote, Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Remote::$remote->expectRequest('POST', '/item/112732201/value/51928207', '', array('title' => 'hello'));
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$tokens->saveToken(5, 123);
Auth::setTokenManager($tokens);
Hook::$hookserver = new Hook('', $a = array('type' => 'item.create',
                                                                 'item_id' => '3',
                                                                ),
                                                       array('PATH_INFO' => '/my/hook'
                                                            ));
$item = new Chiara\PodioApp(array('app_id' => 5), false);

$item->on->my['item.create'] = function($input, $params) use ($test, $a) {
    $test->assertEquals($a, $input, 'input');
    $test->assertEquals(array('hook'), $params, 'params');
    $item = new Chiara\PodioItem(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), null, false);
    $item->fields['title'] = 'hello';
    $item->fields['title']->save();
};
Hook::$hookserver->perform();

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
    'authenticate_with_app' => 
    array (
      0 => 5,
      1 => 123,
    ),
  ),
  2 => 
  array (
    0 => 'post',
    1 => 
    array (
      0 => '/item/112732201/value/51928207',
      1 => 
      array (
        'title' => 'hello',
      ),
      2 => 
      array (
        'hook' => false,
      ),
    ),
  ),
), Chiara\Remote::$remote->queries, 'queries');
echo "done\n";
?>
--EXPECT--
done
