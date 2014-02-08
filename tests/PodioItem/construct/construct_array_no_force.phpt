--TEST--
PodioItem->__construct, array passed in, no force
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
TestRemote::$remote->expectRequest('get', '/item/1', $a = file_get_contents(__DIR__ . '/item.json'));
$item = new Chiara\PodioItem(json_decode($a, 1), null, true);

$test->assertEquals(json_decode($a, 1), $item->info, 'info');
$test->assertEquals(array(), TestRemote::$remote->queries, 'queries executed');
echo "done\n";
?>
--EXPECT--
done
