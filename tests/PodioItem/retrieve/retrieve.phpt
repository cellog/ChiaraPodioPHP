--TEST--
PodioItem->retrieve success
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
TestRemote::$remote->expectRequest('get', '/item/1', file_get_contents(__DIR__ . '/item.json'));
$item = new Chiara\PodioItem(1, null, false);
$item->retrieve();

$test->assertEquals(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), $item->info, 'info');
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
echo "done\n";
?>
--EXPECT--
done