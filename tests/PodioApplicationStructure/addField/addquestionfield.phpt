--TEST--
PodioApplicationStructure->addQuestionField
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addQuestionField('foo', 12345, array(array('status' => 'active', 'text' => 'hi', 'id' => 1, 'color' => 'DCEBD8')), false);

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'question',
    'name' => 'foo',
    'id' => 12345,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => 'hi',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
  12345 => 
  array (
    'type' => 'question',
    'name' => 'foo',
    'id' => 12345,
    'config' => 
    array (
      'options' => 
      array (
        0 => 
        array (
          'status' => 'active',
          'text' => 'hi',
          'id' => 1,
          'color' => 'DCEBD8',
        ),
      ),
      'multiple' => false,
    ),
  ),
), $structure->getRawStructure(), 'after');
?>