--TEST--
HookServer->route test
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$server = new Chiara\HookServer('http://example.com', array('type' => 'item.create'), array('PATH_INFO' => '/one/two/three'));

$test->assertEquals(array (
  'action' => 'one',
  'params' => 
  array (
    0 => 'two',
    1 => 'three',
  ),
), $server->route(), 'basic test');

$server->registerHandler('item.create', 'one/two', function(){});
$server->registerHandler('item.create', 'one/two/three', function(){});

$test->assertEquals(array (
  'action' => 'one/two/three',
  'params' => 
  array (
  ),
), $server->route(), 'one/two/three');

$server->unregisterHandler('item.create', 'one/two/three');

$test->assertEquals(array (
  'action' => 'one/two',
  'params' => 
  array (
    0 => 'three',
  ),
), $server->route(), 'one/two');

echo "done\n";
?>
--EXPECT--
done
