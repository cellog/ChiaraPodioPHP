--TEST--
PodioApplicationStructure->formatValue date
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
include __DIR__ . '/structure.php.inc';
$structure = new Test;

$test->assertEquals(array(array('start' => '2014-03-01 21:34:00')),
                    $structure->formatValue('date', '2014-03-01 21:34'), 'string');

$test->assertEquals(array(array('start' => '2014-03-01 21:34:00')),
                    $structure->formatValue('date', strtotime('2014-03-01 21:34')), 'timestamp');

$test->assertEquals(array(array('start' => '2014-03-01 21:34:00')),
                    $structure->formatValue('date', $d = DateTime::createFromFormat('Y-m-d H:i:s', '2014-03-01 21:34:00')), 'DateTime');

$p = new DatePeriod($d, $d->diff($e = DateTime::createFromFormat('Y-m-d H:i:s', '2015-03-01 21:34:00')), $e->add(new DateInterval('P1D')));

$test->assertEquals(array(array('start' => '2014-03-01 21:34:00', 'end' => '2015-03-01 21:34:00')),
                    $structure->formatValue('date', $p), 'DatePeriod');

$test->assertEquals(array(array('start' => '2014-03-01 21:34:00', 'end' => '2015-03-01 21:34:00')),
                    $structure->formatValue('date', array('start' => '2014-03-01 21:34', 'end' => '2015-03-01 21:34')), 'array');
echo "done\n";
?>
--EXPECT--
done
