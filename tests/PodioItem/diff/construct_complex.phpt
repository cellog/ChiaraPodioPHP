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

$test->assertEquals(1897866, $diff['contact']->added[0]->user_id, 'added userid');
$test->assertEquals(1, count($diff['contact']->added), 'added count');

$test->assertIsa('Chiara\PodioItem\Diff\Fields\Contact', $diff['contact-2'], 'contact-2 class');
$test->assertEquals(107121756, $diff['contact-2']->from[0]->id, 'contact-2 from id');
$test->assertEquals(112147307, $diff['contact-2']->to[0]->id, 'contact-2 to id');

$test->assertEquals(107121756, $diff['contact-2']->deleted[0]->id, 'contact-2 deleted id');
$test->assertEquals(1, count($diff['contact-2']->deleted), 'contact-2 deleted count');

$test->assertEquals(112147307, $diff['contact-2']->added[0]->id, 'contact-2 added id');
$test->assertEquals(1, count($diff['contact-2']->added), 'contact-2 added count');

$test->assertIsa('Chiara\PodioItem\Diff\Fields\Embed', $diff['link'], 'link class');
$test->assertEquals(19611300, $diff['link']->from[0]->id, 'link from id');
$test->assertEquals(23752853, $diff['link']->to[0]->id, 'link to id');

$test->assertEquals(19611300, $diff['link']->deleted[0]->id, 'link deleted id');
$test->assertEquals(1, count($diff['link']->deleted), 'link deleted count');

$test->assertEquals(23752853, $diff['link']->added[0]->id, 'link added id');
$test->assertEquals(1, count($diff['link']->added), 'link added count');
?>
done
--EXPECT--
done