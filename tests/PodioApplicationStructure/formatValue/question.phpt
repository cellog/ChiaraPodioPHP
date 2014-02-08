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
  )
), $structure->formatValue('category', array('id' => 1)), 'category');

$test->assertEquals(array (
  0 => 
  array (
    'value' => 
    array (
      'status' => 'active',
      'text' => '2',
      'id' => 2,
      'color' => 'DCEBD8',
    ),
  )
), $structure->formatValue('category', $option), 'category');

try {
    $structure->formatValue('category', 6, 'non-existing option');
    $test->assertFail('no exception on non-existing option 6');
} catch (Exception $e) {
    $test->assertException($e, 'Exception', 'Option value "6" not found', 'exception on option 6');
}

try {
    $structure->formatValue('question', array(), 'invalid array');
    $test->assertFail('no exception on invalid array');
} catch (Exception $e) {
    $test->assertException($e, 'Exception', 'array passed in is not a valid array for a question or category option', 'exception on invalid array');
}
echo "done\n";
?>
--EXPECT--
done
