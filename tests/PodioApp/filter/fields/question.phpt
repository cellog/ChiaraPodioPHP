--TEST--
PodioApp->filter question field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['question']->add(1);
$filter->fields['question']->add('4');
$filter->fields['question']->add(new Chiara\PodioItem\Values\Option(new Chiara\PodioItem, array('id' => 3)));
$filter->fields['question']->add(array('id' => 4));

$test->assertEquals(array(
    array(
        'key' => 51928215,
        'values' => array(1,2,3,4)
    )
), $filter->view->info['filters'], 'after add');

echo "done\n";
?>
--EXPECT--
done