--TEST--
PodioApplicationStructure->addAppField
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addAppField('foo', 12345, array(15432, 16432));

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'app',
    'name' => 'foo',
    'id' => 12345,
    'config' => array(15432, 16432),
  ),
  12345 => 
  array (
    'type' => 'app',
    'name' => 'foo',
    'id' => 12345,
    'config' => array(15432, 16432),
  ),
), $structure->getRawStructure(), 'after');
echo "done\n";
?>
--EXPECT--
done
