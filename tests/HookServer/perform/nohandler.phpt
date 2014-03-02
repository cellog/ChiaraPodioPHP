--TEST--
HookServer->perform no registered handler
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote;
include __DIR__ . '/../setup.php.inc';
Hook::$hookserver = new Hook('', array('type' => 'item.create',
                                                                 'item_id' => '3',
                                                                ),
                                                       array('PATH_INFO' => '/my/hook'
                                                            ));

try {
    Hook::$hookserver->perform();
    $test->assertFail('no exception thrown');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Unhandled route action for "item.create", action "my"', 'exception');
}

$test->assertEquals(array (), Chiara\Remote::$remote->queries, 'queries');
echo "done\n";
?>
--EXPECT--
done
