--TEST--
PodioItem->toJsonArray, not forced
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$item = new Chiara\PodioItem(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), null, true);
$item->clean();

$test->assertEquals(array(), $item->toJsonArray(), 'output');
echo "done\n";
?>
--EXPECT--
done
