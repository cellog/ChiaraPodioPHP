--TEST--
PodioItem->retrieve success
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
TestRemote::$remote->expectRequest('get', '/app/5/item/2', file_get_contents(__DIR__ . '/item.json'));
$item = new Chiara\PodioItem(null, null, false);
$item->app_id = 5;
$item->app_item_id = 2;
$item->retrieve();

$test->assertEquals(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), $item->info, 'info');
$test->assertEquals(array (
  0 => 
  array (
    0 => 'get',
    1 => 
    array (
      0 => '/app/5/item/2',
      1 => 
      array (
      ),
      2 => 
      array (
      ),
    ),
  ),
), TestRemote::$remote->queries, 'queries executed');
?>