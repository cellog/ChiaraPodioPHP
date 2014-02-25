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
?>
done
--EXPECT--
done