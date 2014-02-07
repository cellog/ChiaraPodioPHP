--TEST--
PodioApplicationStructure->structureFromItem basic test
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => array('app_id' => 3))), $structure->formatValue('app-reference', 3), 'by external id');
$test->assertEquals(array(array('value' => array('app_id' => 3))), $structure->formatValue(51928220, 3), 'by id');

$test->assertEquals(array(
    array('value' => array('app_id' => 2)),
    array('value' => array('app_id' => 3)),
), $structure->formatValue('app-reference', array(2,3)), 'simple array of ids');
echo "done\n";
?>
--EXPECT--
done
