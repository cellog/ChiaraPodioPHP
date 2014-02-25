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

$test->assertIsa('Chiara\PodioItem\Values\Money', $diff['money']->from, 'money from class');
$test->assertIsa('Chiara\PodioItem\Values\Money', $diff['money']->to, 'money to class');

$test->assertEquals(array('currency' => 'USD', 'value' => 3), $diff['money']->from->value, 'money from value');
$test->assertEquals(array('currency' => 'DKK', 'value' => 3), $diff['money']->to->value, 'money to value');

$test->assertEquals(3600, $diff['duration']->from, 'duration from');
$test->assertEquals(3780, $diff['duration']->to, 'duration to');

$test->assertEquals(8, $diff['calculation']->from, 'calculation from');
$test->assertEquals(6, $diff['calculation']->to, 'calculation to');

$test->assertIsa('Chiara\PodioItem\Values\Option', $diff['category']->from, 'category from class');
$test->assertIsa('Chiara\PodioItem\Values\Option', $diff['category']->to, 'category to class');

$test->assertEquals(1, $diff['category']->from->id, 'category from');
$test->assertEquals(2, $diff['category']->to->id, 'category to');

$test->assertIsa('Chiara\PodioItem\Values\Option', $diff['question']->from, 'question from class');
$test->assertIsa('Chiara\PodioItem\Values\Option', $diff['question']->to, 'question to class');

$test->assertEquals(2, $diff['question']->from->id, 'question from');
$test->assertEquals(1, $diff['question']->to->id, 'question to');

$test->assertIsa('Chiara\PodioItem\Values\Date', $diff['date']->from, 'date from class');
$test->assertIsa('Chiara\PodioItem\Values\Date', $diff['date']->to, 'date to class');

$test->assertEquals('2014-01-08 00:00:00', (string) $diff['date']->from, 'date from');
$test->assertEquals('2014-01-22 16:00:00 => 2014-02-25 17:00:00', (string) $diff['date']->to, 'date to');
?>
done
--EXPECT--
done