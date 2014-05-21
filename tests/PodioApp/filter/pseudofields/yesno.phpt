--TEST--
PodioApp->filter yesno pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['yesno']->add('no');

$test->assertEquals(array(
    array(
        'key' => 'yesno',
        'values' => array(
            'no'
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done