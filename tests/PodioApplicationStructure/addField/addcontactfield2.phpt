--TEST--
PodioApplicationStructure->addContactField all_users
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addContactField('foo', 12345, 'all_users');

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'contact',
    'name' => 'foo',
    'id' => 12345,
    'config' => 'all_users',
  ),
  12345 => 
  array (
    'type' => 'contact',
    'name' => 'foo',
    'id' => 12345,
    'config' => 'all_users',
  ),
), $structure->getRawStructure(), 'after');
echo "done\n";
?>
--EXPECT--
done
