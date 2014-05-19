--TEST--
PodioApp->filter duration field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['duration']->from(4.1)->to(6);

$test->assertEquals(array(
    array(
        'key' => 51928218,
        'values' => array(
            'from' => 4,
            'to' => 6,
        )
    )
), $filter->view->info['filters'], 'after add');
$filter->fields['duration']->from('1 hour')->to(60000);

$test->assertEquals(array(
    array(
        'key' => 51928218,
        'values' => array(
            'from' => 3600,
            'to' => 60000,
        )
    )
), $filter->view->info['filters'], 'after add');

$test->runException(function() use ($filter) {
    $filter->fields['duration']->from(-3)->to(8);
}, 'Exception', 'invalid duration "-3", must be > 0', 'negative duration');

echo "done\n";
?>
--EXPECT--
done