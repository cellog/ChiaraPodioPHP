--TEST--
PodioApp->filter fivestar pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['fivestar']->add(1)->add(2);

$test->assertEquals(array(
    array(
        'key' => 'fivestar',
        'values' => array(
            1,2
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done