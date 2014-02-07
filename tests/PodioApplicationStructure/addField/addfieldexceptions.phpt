--TEST--
PodioApplicationStructure->addField exceptions
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;

$structure->addTextField('foo', 12345);

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'text',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
  12345 => 
  array (
    'type' => 'text',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
), $structure->getRawStructure(), 'before');

try {
    $structure->addField('foo', 'foo', 54321);
    $test->assertFail('no exception thrown 1');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Error: field with external-id "foo" already exists', 'same external id');
}

try {
    $structure->addField('foo', 'foor', 12345);
    $test->assertFail('no exception thrown 1');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Error: field with id "12345" already exists', 'same id');
}


$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'text',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
  12345 => 
  array (
    'type' => 'text',
    'name' => 'foo',
    'id' => 12345,
    'config' => NULL,
  ),
), $structure->getRawStructure(), 'after');
echo "done\n";
?>
--EXPECT--
done
