--TEST--
PodioApp->filter created_via pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['created_via']->client('test-client')->podio()->excelImport();

$test->assertEquals(array(
    array(
        'key' => 'created_via',
        'values' => array('test-client', 1, 57)
    )
), $filter->view->info['filters'], 'after created_via');

echo "done\n";
?>
--EXPECT--
done