--TEST--
PodioApp->filter approved pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['approved']->add('yes');

$test->assertEquals(array(
    array(
        'key' => 'approved',
        'values' => array(
            'yes'
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done