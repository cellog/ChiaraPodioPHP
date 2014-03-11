--TEST--
PodioApplicationStructure->formatValue app reference
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => array('app_id' => 3))), $structure->formatValue('app-reference', 3), 'by external id');
$test->assertEquals(array(array('value' => array('app_id' => 3))), $structure->formatValue(51928220, 3), 'by id');

$test->assertEquals(array(
    array('value' => array('app_id' => 2)),
    array('value' => array('app_id' => 3)),
), $structure->formatValue('app-reference', array(2,3)), 'simple array of ids');

$item = new Chiara\PodioItem;
$item->id = 2;

$test->assertEquals(array(
    array('value' => array('app_id' => 2)),
    array('value' => array('app_id' => 3)),
), $structure->formatValue('app-reference', array($item,3)), 'array with object');

class Foo extends Chiara\PodioItem\Field
{
}

$foo = new Foo($item);
$item2 = $item->simpleClone();
$item2->id = 3;
$collection = new Chiara\PodioItem\Values\Collection($foo, array($item, $item2));


$test->assertEquals(array(
    array('value' => array('app_id' => 2)),
    array('value' => array('app_id' => 3)),
), $structure->formatValue('app-reference', $collection
                           ), 'collection');

echo "done\n";
?>
--EXPECT--
done
