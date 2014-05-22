--TEST--
PodioApp->filter last_edit_via pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['last_edit_via']->client('test-client')->podio()->excelImport();

$test->assertEquals(array(
    array(
        'key' => 'last_edit_via',
        'values' => array('test-client', 1, 57)
    )
), $filter->view->info['filters'], 'after last_edit_via');

echo "done\n";
?>
--EXPECT--
done