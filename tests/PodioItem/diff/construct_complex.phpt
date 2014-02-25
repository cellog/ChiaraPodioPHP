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

$test->assertIsa('Chiara\PodioItem\Diff\Fields\Image', $diff['image'], 'image class');
$test->assertEquals(76482653, $diff['image']->from[0]->id, 'image from id');
$test->assertEquals(83568354, $diff['image']->to[0]->id, 'image to id');

$test->assertEquals(76482653, $diff['image']->deleted[0]->id, 'image deleted id');
$test->assertEquals(1, count($diff['image']->deleted), 'image deleted count');

$test->assertEquals(83568354, $diff['image']->added[0]->id, 'image added id');
$test->assertEquals(1, count($diff['image']->added), 'image added count');

$test->assertIsa('Chiara\PodioItem\Diff\Fields\Location', $diff['google-maps'], 'google-maps class');
$test->assertEquals("1 Lincoln Center Plaza, New York, NY 10023", $diff['google-maps']->from[0]->value, 'google-maps from id');
$test->assertEquals("15 W. 13th st., new york, ny", $diff['google-maps']->to[0]->value, 'google-maps to id');

$test->assertEquals("1 Lincoln Center Plaza, New York, NY 10023", $diff['google-maps']->deleted[0]->value, 'google-maps deleted id');
$test->assertEquals(1, count($diff['google-maps']->deleted), 'google-maps deleted count');

$test->assertEquals("15 W. 13th st., new york, ny", $diff['google-maps']->added[0]->value, 'google-maps added id');
$test->assertEquals(1, count($diff['google-maps']->added), 'google-maps added count');

$test->assertIsa('Chiara\PodioItem\Diff\Fields\App', $diff['app-reference'], 'app-reference class');
$test->assertEquals(112732193, $diff['app-reference']->from[0]->id, 'app-reference from id');
$test->assertEquals(125266328, $diff['app-reference']->to[0]->id, 'app-reference to id');

$test->assertEquals(112732193, $diff['app-reference']->deleted[0]->id, 'app-reference deleted id');
$test->assertEquals(1, count($diff['app-reference']->deleted), 'app-reference deleted count');

$test->assertEquals(125266328, $diff['app-reference']->added[0]->id, 'app-reference added id');
$test->assertEquals(1, count($diff['app-reference']->added), 'app-reference added count');
?>
done
--EXPECT--
done