--TEST--
PodioItem empty field should be accessible if the structure has it
--FILE--
<?php
use Chiara\HookServer as Hook, Chiara\Remote as Remote, Chiara\AuthManager as Auth;
include __DIR__ . '/../setup.php.inc';
Auth::setAuthMode(Auth::APP);
$tokens = new TestTokenManager;
$item = new Chiara\PodioItem();
$tokens->saveToken(1, 123);
Auth::setTokenManager($tokens);
$structure = new Chiara\PodioApplicationStructure;
$structure->addNumberField('foo', 1234);
$item = new Chiara\PodioItem(array(
    'item_id' => 1235,
    'app' => array('app_id' => 1),
    'fields' => array()
                                  ), $structure);
$test->assertEquals(null, $item->fields['foo']->value, 'before setting, confirm we can access it');
$item->fields['foo'] = 4;
$test->assertEquals(4, $item->fields['foo']->value, 'confirm setting works');
?>
done
--EXPECT--
done