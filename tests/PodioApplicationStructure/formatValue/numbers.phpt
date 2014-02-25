--TEST--
PodioApplicationStructure->formatValue number/money/duration/progress
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('value' => 5)),
                    $structure->formatValue('number', 5), 'number');

$test->assertEquals(array(array('value' => 5)),
                    $structure->formatValue('progress-slider', 5), 'progress success 5');

$test->assertEquals(array(array('value' => 0)),
                    $structure->formatValue('progress-slider', 0), 'progress success 0');

$test->assertEquals(array(array('value' => 100)),
                    $structure->formatValue('progress-slider', 100), 'progress success 100');

$test->assertEquals(array(array('value' => 100)),
                    $structure->formatValue('duration', 100), 'duration integer');

$test->assertEquals(array(array('value' => 86400)),
                    $structure->formatValue('duration', '1 day'), 'date interval');

$test->assertEquals(array(array('value' => 3600)),
                    $structure->formatValue('duration', DateInterval::createFromDateString('1 hour')), 'date interval object');

$test->assertEquals(array(array('currency' => 'USD', 'value' => 50.14)),
                    $structure->formatValue('money', 50.14), 'simple integer money');

$test->assertEquals(array(array('currency' => 'USD', 'value' => 50.14)),
                    $structure->formatValue('money', '$50.14'), 'simple integer money');

try {
    $structure->formatValue('progress-slider', -1);
    $test->assertFail('no exception on -1');
} catch (Exception $e) {
    $test->assertException($e, 'Exception', 'progress field must be between 0 and 100', 'progress -1');
}

try {
    $structure->formatValue('progress-slider', 101);
    $test->assertFail('no exception on 101');
} catch (Exception $e) {
    $test->assertException($e, 'Exception', 'progress field must be between 0 and 100', 'progress 101');
}

try {
    $structure->formatValue('number', 'a');
    $test->assertFail('no exception on "a"');
} catch (Exception $e) {
    $test->assertException($e, 'Exception', 'Cannot set a number to a non-numeric value', 'number to "a"');
}
echo "done\n";
?>
--EXPECT--
done
