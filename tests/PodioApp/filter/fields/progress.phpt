--TEST--
PodioApp->filter progress field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['progress-slider']->from(0)->to(100);

$test->assertEquals(array(
    array(
        'key' => 51928219,
        'values' => array(
            'from' => 0,
            'to' => 100,
        )
    )
), $filter->view->info['filters'], 'bounds');
$filter->fields['progress-slider']->from(2.3)->to(50);

$test->assertEquals(array(
    array(
        'key' => 51928219,
        'values' => array(
            'from' => 2,
            'to' => 50,
        )
    )
), $filter->view->info['filters'], 'float to int');

$test->runException(function() use ($filter) {
    $filter->fields['progress-slider']->from(-3)->to(8);
}, 'Exception', 'invalid progress value "-3"', 'negative progress');

echo "done\n";
?>
--EXPECT--
done