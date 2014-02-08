--TEST--
PodioItem->toJsonArray, full test of each value
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$item = new Chiara\PodioItem(json_decode(file_get_contents(__DIR__ . '/item.json'), 1), null, true);
$test->assertEquals(array (
  'title' => 'test',
  'contact' => 
  array (
    0 => 98265149,
  ),
  'contact-2' => 
  array (
    0 => 107121756,
  ),
  'category' => 1,
  'date' => 
  array (
    'start' => '2014-01-08 00:00:00',
  ),
  'link' => 
  array (
    0 => 
    array (
      'url' => 'http://chiaraquartet.net',
    ),
  ),
  'image' => 
  array (
    0 => 76482653,
  ),
  'google-maps' => 
  array (
    0 => '1 Lincoln Center Plaza, New York, NY 10023',
  ),
  'question' => 2,
  'number' => '5.0000',
  'money' => 
  array (
    'currency' => 'USD',
    'value' => '3.0000',
  ),
  'duration' => 3600,
  'progress-slider' => 25,
  'app-reference' => 
  array (
    0 => 112732193,
  ),
), $item->toJsonArray(), 'output');
echo "done\n";
?>
--EXPECT--
done
