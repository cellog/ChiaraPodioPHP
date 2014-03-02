--TEST--
HookServer->validHook test of all valid values
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$server = new Chiara\HookServer;
try {
    $server->validHook('item.glom');
    $test->assertFail('No exception thrown');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Invalid podio hook action: "item.glom"', 'exception');
}
echo "done\n";
?>
--EXPECT--
done
