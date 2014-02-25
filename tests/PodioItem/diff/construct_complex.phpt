--TEST--
PodioItem revision diff iterator test, complex diff call
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
Chiara\Remote::$remote->expectRequest('GET', '/item/112732201/revision/2/1', file_get_contents(__DIR__ . '/revisions.json'));
$item = new Chiara\PodioItem($a = json_decode(file_get_contents(__DIR__ . '/item.json'), 1));
$structure = Chiara\PodioApplicationStructure::fromItem($item);
$item = new $item($a, $structure);
$diff = $item->diff(1);
$test->assertIsa('Chiara\Iterators\ItemRevisionDiffIterator', $diff, 'class of diff');

$test->assertIsa('Chiara\PodioItem\Diff\Fields\Contact', $diff['contact'], 'contact class');
$test->assertEquals(1834391, $diff['contact']->from[0]->user_id, 'from userid');
$test->assertEquals(1897866, $diff['contact']->to[0]->user_id, 'to userid');
$test->assertEquals(1834391, $diff['contact']->deleted[0]->user_id, 'deleted userid');
$test->assertEquals(1, count($diff['contact']->deleted), 'deleted count');
?>
done
--EXPECT--
done