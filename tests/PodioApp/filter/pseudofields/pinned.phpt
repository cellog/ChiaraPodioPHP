--TEST--
PodioApp->filter pinned pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['pinned']->isTrue();

$test->assertEquals(array(
    array(
        'key' => 'pinned',
        'values' => true
    )
), $filter->view->info['filters'], 'after true');
$filter->pseudofields['pinned']->isFalse();

$test->assertEquals(array(
    array(
        'key' => 'pinned',
        'values' => false
    )
), $filter->view->info['filters'], 'after false');

echo "done\n";
?>
--EXPECT--
done