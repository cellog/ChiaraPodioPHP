<?php
include dirname(__DIR__) . '/autoload.php';

// format {"client":"your-client-id","token":"your api token"}
$config = json_decode(file_get_contents(__DIR__ . '/api.json'), 1);

Podio::setup($config['client'], $config['token']);
Podio::authenticate_with_app(6686618, '6b236efd6920431687b139fddab701e2');

$app = new Chiara\PodioApp(6686618);
$app->dump();