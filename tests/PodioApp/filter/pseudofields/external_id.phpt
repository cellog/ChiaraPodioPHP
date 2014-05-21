--TEST--
PodioApp->filter external_id pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['external_id']->add(4)->add(6);

$test->assertEquals(array(
    array(
        'key' => 'external_id',
        'values' => array(
            4,6
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done