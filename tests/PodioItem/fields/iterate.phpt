--TEST--
PodioItem app reference iteration test
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote, Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Remote::$remote->expectRequest('GET', '/item/125266328', file_get_contents(__DIR__ . '/item2.json'));
Remote::$remote->expectRequest('GET', '/item/125266331', file_get_contents(__DIR__ . '/item3.json'));
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$item = new Chiara\PodioItem($a = json_decode(file_get_contents(__DIR__ . '/item.json'), 1));
$tokens->saveToken($item->info['app']['app_id'], 123);
Auth::setTokenManager($tokens);
$structure = Chiara\PodioApplicationStructure::fromItem($item);
$item = new $item($a, $structure);

$ids = array();
foreach ($item->fields['app-reference'] as $iter) {
    // verify that we can load the item and successfully generate structure
    $iter->fields['title'] = 'hi';
    $ids[] = $iter->id;
}
$test->assertEquals(array (
  0 => 125266328,
  1 => 125266331,
), $ids, 'verify we get the items as expected');
?>
done
--EXPECT--
done