--TEST--
PodioApp->filter created_by pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['created_by']->user(4)->app(5);

$test->assertEquals(array(
    array(
        'key' => 'created_by',
        'values' => array(array(
            'type' => 'user',
            'id' => 4,
        ),
        array(
            'type' => 'app',
            'id' => 5,
        ))
    )
), $filter->view->info['filters'], 'after created_by');

$filter->pseudofields['created_by']->reset()->user(array('user_id' => 5));

$test->assertEquals(array(
    array(
        'key' => 'created_by',
        'values' => array(array(
            'type' => 'user',
            'id' => 5,
        ))
    )
), $filter->view->info['filters'], 'after created_by array');

$filter->pseudofields['created_by']->reset()->user(new Chiara\PodioContact(array('user_id' => 6, 'profile_id' => 7)));

$test->assertEquals(array(
    array(
        'key' => 'created_by',
        'values' => array(array(
            'type' => 'user',
            'id' => 6,
        ))
    )
), $filter->view->info['filters'], 'after created_by object');

$filter->pseudofields['created_by']->reset()->me();

$test->assertEquals(array(
    array(
        'key' => 'created_by',
        'values' => array(array(
            'type' => 'user',
            'id' => 0,
        ))
    )
), $filter->view->info['filters'], 'after created_by me');

echo "done\n";
?>
--EXPECT--
done