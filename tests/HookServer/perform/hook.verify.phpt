--TEST--
HookServer->perform hook.verify
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
Chiara\HookServer::$hookserver = new Chiara\HookServer('', array('type' => 'hook.verify',
                                                                 'code' => '12345',
                                                                 'hook_id' => '3'
                                                                ),
                                                       array('PATH_INFO' => '/verify/my/hook'
                                                            ));
Chiara\Remote::$remote->expectRequest('POST', '/hook/3/verify/validate', 'hi', array('code' => '12345'));

Chiara\HookServer::$hookserver->perform();

$test->assertEquals(array (
  0 => 
  array (
    0 => 'post',
    1 => 
    array (
      0 => '/hook/3/verify/validate',
      1 => 
      array (
        'code' => '12345',
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
