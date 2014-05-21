--TEST--
PodioApp->filter tags pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['tags']->add('hi')->add('there');

$test->assertEquals(array(
    array(
        'key' => 'tags',
        'values' => array(
            'hi', 'there'
        )
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done