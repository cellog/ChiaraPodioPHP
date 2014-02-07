--TEST--
PodioApplicationStructure->addCalculationField
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addCalculationField('foo', 12345);

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'calculation',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
  12345 => 
  array (
    'type' => 'calculation',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
), $structure->getRawStructure(), 'after');
echo "done\n";
?>
--EXPECT--
done
