--TEST--
HookServer->validHook test of all valid values
--FILE--
<?php
include __DIR__ . '/../setup.php.inc';
$server = new Chiara\HookServer;
$server->validHook('item.create');
$server->validHook('item.update');
$server->validHook('item.delete');
$server->validHook('comment.create');
$server->validHook('comment.delete');
$server->validHook('file.change');
$server->validHook('app.update');
$server->validHook('app.delete');
$server->validHook('app.create');
$server->validHook('task.create');
$server->validHook('task.update');
$server->validHook('task.delete');
$server->validHook('member.add');
$server->validHook('member.remove');
$server->validHook('hook.verify');
echo "done\n";
?>
--EXPECT--
done
