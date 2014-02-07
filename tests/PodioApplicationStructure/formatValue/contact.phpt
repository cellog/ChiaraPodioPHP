--TEST--
PodioApplicationStructure->formatValue contact
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => array('profile_id' => 3))), $structure->formatValue('contact', 3), 'by external id');
$test->assertEquals(array(array('value' => array('profile_id' => 3))), $structure->formatValue(51928208, 3), 'by id');

$test->assertEquals(array(
    array('value' => array('profile_id' => 2)),
    array('value' => array('profile_id' => 3)),
), $structure->formatValue('contact', array(2,3)), 'simple array of ids');

$item = new Chiara\PodioContact(null, false);
$item->id = 2;

$test->assertEquals(array(
    array('value' => array('profile_id' => 2)),
    array('value' => array('profile_id' => 3)),
), $structure->formatValue('contact', array($item,3)), 'array with object');

class Foo extends Chiara\PodioItem\Field
{
}

$foo = new Foo(new Chiara\PodioItem);
$item2 = clone $item;
$item2->id = 3;
$collection = new Chiara\PodioItem\Values\Collection($foo, array($item, $item2));


$test->assertEquals(array(
    array('value' => array('profile_id' => 2)),
    array('value' => array('profile_id' => 3)),
), $structure->formatValue('contact', $collection
                           ), 'collection');

echo "done\n";
?>
--EXPECT--
done
