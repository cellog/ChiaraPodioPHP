--TEST--
PodioApp->filter last_edit_by pseudofield
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->pseudofields['last_edit_by']->user(4);

$test->assertEquals(array(
    array(
        'key' => 'last_edit_by',
        'values' => array(
            'type' => 'user',
            'id' => 4,
        )
    )
), $filter->view->info['filters'], 'after last_edit_by');

$filter->pseudofields['last_edit_by']->user(array('user_id' => 5));

$test->assertEquals(array(
    array(
        'key' => 'last_edit_by',
        'values' => array(
            'type' => 'user',
            'id' => 5,
        )
    )
), $filter->view->info['filters'], 'after last_edit_by array');

$filter->pseudofields['last_edit_by']->user(new Chiara\PodioContact(array('user_id' => 6, 'profile_id' => 7)));

$test->assertEquals(array(
    array(
        'key' => 'last_edit_by',
        'values' => array(
            'type' => 'user',
            'id' => 6,
        )
    )
), $filter->view->info['filters'], 'after last_edit_by object');

$filter->pseudofields['last_edit_by']->me();

$test->assertEquals(array(
    array(
        'key' => 'last_edit_by',
        'values' => array(
            'type' => 'user',
            'id' => 0,
        )
    )
), $filter->view->info['filters'], 'after last_edit_by me');

echo "done\n";
?>
--EXPECT--
done