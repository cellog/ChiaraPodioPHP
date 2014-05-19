--TEST--
PodioApp->filter basic, set a filter and sort option
--FILE--
<?php
use Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$item = new Chiara\PodioItem();
$tokens->saveToken(1, 123);
$tokens->saveToken(6686618, 123);
Auth::setTokenManager($tokens);
TestRemote::$remote->expectRequest('get', '/item/1', $x = file_get_contents(__DIR__ . '/item.json'));
$x = json_decode($x, 1);
$filterjson = json_decode(file_get_contents(__DIR__ . '/filter.json'), 1);
for ($i = 0; $i < 30; $i++) {
    $filterjson['items'][$i] = $x;
    $filterjson['items'][$i]['title'] = 'item ' . $i;
    $filterjson['items'][$i]['fields'][0]['values'][0]['value'] = 'item ' . $i;
}
TestRemote::$remote->expectRequest('post', '/item/app/6686618/filter/', json_encode($filterjson, 1), array (
  'filters' => 
  array (
    0 => 
    array (
      'key' => 51928210,
      'values' => 
      array (
        0 => 1,
      ),
    ),
    1 => 
    array (
      'key' => 51928211,
      'values' => 
      array (
        'from' => '-3wr',
        'to' => '+0dr',
      ),
    ),
  ),
  'sort_by' => 'created_on',
  'sort_desc' => '1',
  'limit' => 30,
));
$j = $filterjson;
$j['items'] = array();
for ($i = 30; $i < 41; $i++) {
    $j['items'][$i - 30] = $x;
    $j['items'][$i - 30]['title'] = 'item ' . $i;
    $j['items'][$i - 30]['fields'][0]['values'][0]['value'] = 'item ' . $i;
}
TestRemote::$remote->expectRequest('post', '/item/app/6686618/filter/', json_encode($j, 1), array (
  'filters' => 
  array (
    0 => 
    array (
      'key' => 51928210,
      'values' => 
      array (
        0 => 1,
      ),
    ),
    1 => 
    array (
      'key' => 51928211,
      'values' => 
      array (
        'from' => '-3wr',
        'to' => '+0dr',
      ),
    ),
  ),
  'sort_by' => 'created_on',
  'sort_desc' => '1',
  'limit' => 30,
  'offset' => 30,
));
TestRemote::$remote->expectRequest('get', '/app/6686618', $x = file_get_contents(__DIR__ . '/app.json'));

$item = new Chiara\PodioItem(1, null, false);
$item->app_id = 1;
$item->retrieve();
$refs = array();
$filter = $item->app->filter;
$filter->fields['category']->add(1);
$filter->fields['date']->past(3)->weeks();
foreach ($item->app->filter as $ref) {
    $refs[] = $ref->title;
}

$test->assertEquals(array (
  0 => 'item 0',
  1 => 'item 1',
  2 => 'item 2',
  3 => 'item 3',
  4 => 'item 4',
  5 => 'item 5',
  6 => 'item 6',
  7 => 'item 7',
  8 => 'item 8',
  9 => 'item 9',
  10 => 'item 10',
  11 => 'item 11',
  12 => 'item 12',
  13 => 'item 13',
  14 => 'item 14',
  15 => 'item 15',
  16 => 'item 16',
  17 => 'item 17',
  18 => 'item 18',
  19 => 'item 19',
  20 => 'item 20',
  21 => 'item 21',
  22 => 'item 22',
  23 => 'item 23',
  24 => 'item 24',
  25 => 'item 25',
  26 => 'item 26',
  27 => 'item 27',
  28 => 'item 28',
  29 => 'item 29',
  30 => 'item 30',
  31 => 'item 31',
  32 => 'item 32',
  33 => 'item 33',
  34 => 'item 34',
  35 => 'item 35',
  36 => 'item 36',
  37 => 'item 37',
  38 => 'item 38',
  39 => 'item 39',
), $refs, 'verify retrieved items');

$test->assertEquals(array (
  0 => 
  array (
    'setup' => 
    array (
      0 => 5,
      1 => 6,
      2 => 
      array (
        'session_manager' => 'PodioSession',
        'curl_options' => 
        array (
        ),
      ),
    ),
  ),
  1 => 
  array (
    'authenticate_with_app' => 
    array (
      0 => 1,
      1 => 123,
    ),
  ),
  2 => 
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
  3 => 
  array (
    'authenticate_with_app' => 
    array (
      0 => 6686618,
      1 => 123,
    ),
  ),
  4 => 
  array (
    0 => 'get',
    1 => 
    array (
      0 => '/app/6686618',
      1 => 
      array (
      ),
      2 => 
      array (
      ),
    ),
  ),
  5 => 
  array (
    0 => 'post',
    1 => 
    array (
      0 => '/item/app/6686618/filter/',
      1 => 
      array (
        'filters' => 
        array (
          0 => 
          array (
            'key' => 51928210,
            'values' => 
            array (
              0 => 1,
            ),
          ),
          1 => 
          array (
            'key' => 51928211,
            'values' => 
            array (
              'from' => '-3wr',
              'to' => '+0dr',
            ),
          ),
        ),
        'sort_by' => 'created_on',
        'sort_desc' => '1',
        'limit' => 30,
      ),
      2 => 
      array (
      ),
    ),
  ),
  6 => 
  array (
    0 => 'post',
    1 => 
    array (
      0 => '/item/app/6686618/filter/',
      1 => 
      array (
        'filters' => 
        array (
          0 => 
          array (
            'key' => 51928210,
            'values' => 
            array (
              0 => 1,
            ),
          ),
          1 => 
          array (
            'key' => 51928211,
            'values' => 
            array (
              'from' => '-3wr',
              'to' => '+0dr',
            ),
          ),
        ),
        'sort_by' => 'created_on',
        'sort_desc' => '1',
        'limit' => 30,
        'offset' => 30,
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