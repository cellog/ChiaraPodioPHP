--TEST--
PodioApp->filter thumbs pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['thumbs']->add('up');

$test->assertEquals(array(
    array(
        'key' => 'thumbs',
        'values' => array(
            'up'
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done