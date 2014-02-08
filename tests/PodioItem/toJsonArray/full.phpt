--TEST--
PodioItem->toJsonArray, full test of each value
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$item = new Chiara\PodioItem(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), null, true);
$test->assertEquals(array('number' => 6), $item->toJsonArray(), 'output');
echo "done\n";
?>
--EXPECT--
done
