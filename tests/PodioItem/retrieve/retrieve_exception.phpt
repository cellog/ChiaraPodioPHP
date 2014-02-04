--TEST--
PodioItem->retrieve with exception thrown
--FILE--
<?php
include __DIR__ . '/setup.php.inc';
$item = new Chiara\PodioItem;
try {
    $item->retrieve();
    $test->assertFail('no exception thrown');
} catch (\Exception $e) {
    $test->assertException($e, 'Exception', 'Cannot retrieve item, no item_id or app_item_id', 'wrong error message');
}
?>