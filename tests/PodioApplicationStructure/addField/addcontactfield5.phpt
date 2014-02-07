--TEST--
PodioApplicationStructure->addContactField exceptions
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$structure = new Chiara\PodioApplicationStructure;
$test->assertEquals(array(), $structure->getRawStructure(), 'before');

try {
    $structure->addContactField('foo', 12345, 'gronk');
    $test->assertFail('no exception 1');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Invalid type "gronk" for contact field "foo"', 'exception message');
}

$test->assertEquals(array(), $structure->getRawStructure(), 'after');
echo "done\n";
?>
--EXPECT--
done
