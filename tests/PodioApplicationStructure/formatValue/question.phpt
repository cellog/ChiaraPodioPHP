--TEST--
PodioApplicationStructure->formatValue category/question
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array (
  0 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '3',
      'id' => 1,
      'color' => 'DCEBD8',
    ),
  ),
), $structure->formatValue('question', 1), 'question');

$test->assertEquals(array (
  0 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '1',
      'id' => 1,
      'color' => 'DCEBD8',
    ),
  ),
  1 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '2',
      'id' => 2,
      'color' => 'DCEBD8',
    ),
  ),
), $structure->formatValue('category', array(1, 2)), 'category');

$option = new Chiara\PodioItem\Values\Option(new Chiara\PodioItem,     array('value' => array (
      'status' => 'active',
      'text' => '2',
      'id' => 2,
      'color' => 'DCEBD8',
    )));

$test->assertEquals(array (
  0 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '1',
      'id' => 1,
      'color' => 'DCEBD8',
    ),
  ),
  1 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '2',
      'id' => 2,
      'color' => 'DCEBD8',
    ),
  )
), $structure->formatValue('category', array(array('id' => 1), $option)), 'category');

echo "done\n";
?>
--EXPECT--
done
