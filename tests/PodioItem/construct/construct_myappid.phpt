--TEST--
PodioItem->__construct, child class, MYAPPID is set
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
class foo extends Chiara\PodioItem
{
    const MYAPPID = 12345;
}
$item = new foo(3, null, false);

$test->assertEquals(3, $item->info['item_id'], 'item_id');
$test->assertEquals(12345, $item->info['app']['app_id'], 'app_id');

$item = new foo(array('item_id' => 3), null, false);

$test->assertEquals(3, $item->info['item_id'], 'item_id');
$test->assertEquals(12345, $item->info['app']['app_id'], 'app_id');

try {
    $item = new foo(array('item_id' => 3, 'app' => array('app_id' => 4)), null, false);
    $test->assertFail('no exception thrown');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'foo item has app id set to 4, but it must be 12345', 'app id is wrong');
}
?>