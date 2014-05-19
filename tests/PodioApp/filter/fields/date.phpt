--TEST--
PodioApp->filter date field
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$app = new Chiara\PodioApp(json_decode(file_get_contents(__DIR__ . '/app.json'), 1));
$filter = $app->filter;
$filter->fields['date']->from('2013-03-04 12:34')->to('yesterday');

$d = new \DateTime('yesterday');
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '2013-03-04',
            'to' => $d->format('Y-m-d'),
        )
    )
), $filter->view->info['filters'], 'after add');

$filter->fields['date']->past(3)->days();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3dr',
            'to' => $d->format('Y-m-d'),
        )
    )
), $filter->view->info['filters'], 'past 3 days no reset');

$filter->fields['date']->reset()->past(3)->days();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3dr',
            'to' => '+0dr',
        )
    )
), $filter->view->info['filters'], 'past 3 days');

$filter->fields['date']->reset()->future(3)->days();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '+0dr',
            'to' => '+3dr',
        )
    )
), $filter->view->info['filters'], 'future 3 days');

$filter->fields['date']->reset()->past(3)->days()->notRounded()->future(3)->days();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3d',
            'to' => '+3dr',
        )
    )
), $filter->view->info['filters'], 'past 3 days not rounded future 3 days');

$filter->fields['date']->reset()->past(3)->days()->future(3)->days()->notRounded();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3dr',
            'to' => '+3d',
        )
    )
), $filter->view->info['filters'], 'past 3 days future 3 days not rounded');

$filter->fields['date']->reset()->past(3)->weeks();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3wr',
            'to' => '+0dr',
        )
    )
), $filter->view->info['filters'], 'past 3 weeks');

$filter->fields['date']->reset()->past(3)->months();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3mr',
            'to' => '+0dr',
        )
    )
), $filter->view->info['filters'], 'past 3 months');

$filter->fields['date']->reset()->past(3)->years();
$test->assertEquals(array(
    array(
        'key' => 51928211,
        'values' => array(
            'from' => '-3yr',
            'to' => '+0dr',
        )
    )
), $filter->view->info['filters'], 'past 3 years');

echo "done\n";
?>
--EXPECT--
done