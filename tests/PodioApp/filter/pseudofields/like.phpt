--TEST--
PodioApp->filter like pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['like']->isTrue();

$test->assertEquals(array(
    array(
        'key' => 'like',
        'values' => true
    )
), $filter->view->info['filters'], 'after true');
$filter->pseudofields['like']->isFalse();

$test->assertEquals(array(
    array(
        'key' => 'like',
        'values' => false
    )
), $filter->view->info['filters'], 'after false');

echo "done\n";
?>
--EXPECT--
done