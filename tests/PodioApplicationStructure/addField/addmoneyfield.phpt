--TEST--
PodioApplicationStructure->addMoneyField
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addMoneyField('foo', 12345, array('USD'));

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'money',
    'name' => 'foo',
    'id' => 12345,
    'config' => array('USD'),
  ),
  12345 => 
  array (
    'type' => 'money',
    'name' => 'foo',
    'id' => 12345,
    'config' => array('USD'),
  ),
), $structure->getRawStructure(), 'after');
?>