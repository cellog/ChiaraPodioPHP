--TEST--
PodioApp->filter contact field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['contact']->add(1);
$filter->fields['contact']->add(new Chiara\PodioContact(12345, null, false));
$filter->fields['contact']->add(array('profile_id' => 23456));

$test->assertEquals(array(
    array(
        'key' => 51928208,
        'values' => array(1,12345,23456)
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done