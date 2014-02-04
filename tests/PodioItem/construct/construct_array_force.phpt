--TEST--
PodioItem->__construct, array passed in, force
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
TestRemote::$remote->expectRequest('get', '/item/1', $a = file_get_contents(__DIR__ . '/item.json'));
$item = new Chiara\PodioItem(array('item_id' => 1, 'app' => array('app_id'=>2)), null, 'force');

$test->assertEquals(json_decode($a, 1), $item->info, 'info');
$test->assertEquals(array (
  0 => 
  array (
    0 => 'get',
    1 => 
    array (
      0 => '/item/1',
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