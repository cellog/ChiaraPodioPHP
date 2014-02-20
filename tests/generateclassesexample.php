<?php
include dirname(__DIR__) . '/autoload.php';

// format {"client":"your-client-id","token":"your api token"}
$config = json_decode(file_get_contents(__DIR__ . '/mylocaltest.json'), 1);

$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/localtokens.json', __DIR__ . '/mylocaltest.json', true);
Chiara\AuthManager::setTokenManager($tokenmanager);
Chiara\AuthManager::attemptServerLogin('http://localhost' . $_SERVER['PHP_SELF'], isset($_GET['logout']),
                                       isset($_GET['code']) ? $_GET['code'] : false);

// This example shows how helper classes can be generated for a specific subset of workspaces
// in 3 lines of code (the 4th is just for informational purposes)
$myorganizations = Chiara\PodioOrganization::mine();
foreach ($myorganizations['unledu']->workspaces->matching('^SOM: Chamber Music') as $space) {
    $ret = $space->generateClasses(__DIR__ . '/modeltest', 'SOM\Models', null, 'SOM\MyTest');
    echo $space, " processed<br>\n";
}