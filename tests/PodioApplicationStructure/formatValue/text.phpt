--TEST--
PodioApplicationStructure->formatValue text/location
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => 'hi')),
                    $structure->formatValue('title', 'hi'), 'text');

$test->assertEquals(array(array('value' => 'address thingy')),
                    $structure->formatValue('google-maps', 'address thingy'), 'location');

echo "done\n";
?>
--EXPECT--
done
