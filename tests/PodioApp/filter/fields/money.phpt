--TEST--
PodioApp->filter money field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
TestRemote::$remote->expectRequest('get', '/item/field/51928217/range', json_encode(array('min' => 5, 'max' => 7), 1));
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['money']->from(4)->to(6);

$test->assertEquals(array(
    array(
        'key' => 51928217,
        'values' => array(
            'from' => 4.0,
            'to' => 6.0,
        )
    )
), $filter->view->info['filters'], 'after add');

$filter->fields['money']->verifyPossible()->from(5)->to(6);

$test->assertEquals(array(
    array(
        'key' => 51928217,
        'values' => array(
            'from' => 5.0,
            'to' => 6.0,
        )
    )
), $filter->view->info['filters'], 'after add');

$test->runException(function() use ($filter) {
    $filter->fields['money']->verifyPossible()->from(5)->to(8);
}, 'Exception', 'Cannot use value "8", it is not within the range of possible field values "5"->"7"', 'outside range');

echo "done\n";
?>
--EXPECT--
done