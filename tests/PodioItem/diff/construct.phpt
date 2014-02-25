--TEST--
PodioItem revision diff iterator test, basic diff call
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
Chiara\Remote::$remote->expectRequest('GET', '/item/112732201/revision/2/1', file_get_contents(__DIR__ . '/revisions.json'));
$item = new Chiara\PodioItem($a = json_decode(file_get_contents(__DIR__ . '/item.json'), 1));
$structure = Chiara\PodioApplicationStructure::fromItem($item);
$item = new $item($a, $structure);
$diff = $item->diff(1);
$test->assertIsa('Chiara\Iterators\ItemRevisionDiffIterator', $diff, 'class of diff');

$test->assertEquals(5, $diff['number']->from, 'number from');
$test->assertEquals(3, $diff['number']->to, 'number from');
?>
done
--EXPECT--
done