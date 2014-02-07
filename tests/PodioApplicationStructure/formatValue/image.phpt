--TEST--
PodioApplicationStructure->formatValue image
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => array('link' => 'http://example.com/img.jpg'))),
                    $structure->formatValue('image', 'http://example.com/img.jpg'), 'url');

$test->assertEquals(array(array('value' => array('file_id' => 1))),
                    $structure->formatValue('image', 1), 'file id');
echo "done\n";
?>
--EXPECT--
done
