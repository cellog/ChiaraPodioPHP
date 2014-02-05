--TEST--
PodioApplicationStructure->addContactField space_users_and_contacts
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

$structure->addContactField('foo', 12345, 'space_users_and_contacts');

$test->assertEquals(array (
  'foo' => 
  array (
    'type' => 'contact',
    'name' => 'foo',
    'id' => 12345,
    'config' => 'space_users_and_contacts',
  ),
  12345 => 
  array (
    'type' => 'contact',
    'name' => 'foo',
    'id' => 12345,
    'config' => 'space_users_and_contacts',
  ),
), $structure->getRawStructure(), 'after');
?>