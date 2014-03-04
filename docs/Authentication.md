ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Authentication

The authentication enhancements in the Chiara library are conveniences built
on top of the existing authentication infrastructure.

Often, when writing a web hook, one may need to access more than one app in
order to retrieve the information needed to execute the hook.  As the most
secure way to authenticate is with limited access, app authentication is the
preferred method for performing these tasks.  Unfortunately, before any item
from another app can be retrieved, we must explicitly authorize as that app.

The ChiaraPodioPHP library automatically authorizes as the correct app
before retrieving an item, as long as it knows the app_id of that item.
For any app reference field, or any external reference from another app's
app reference field, this information is built right into the meta-data downloaded
with the item, and so no programming is necessary to ensure that authorization will
be available.

##Security

Another consideration is the security of app tokens.  Every app has a public
app_id and a private token, which can be regenerated as necessary.  The token
must be kept secret, and so should never be stored directly in source code,
especially if the source code is stored on a public source control server such
as [github](http://github.com).

The ChiaraPodioPHP library provides an infrastructure to store and retrieve
tokens for an application.  Currently, only a file-based system is coded, but
the library is extensible.  New drivers for a database-driven system can be
easily coded and plugged into the existing infrastructure.  Simply define
a class that implements the [Chiara\Interfaces\AuthTokenManager](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/Interfaces/AuthTokenManager.php)
interface and match the return values to the example AuthManager
[Chiara\AuthManager\File](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/AuthManager/File.php)
class.

Here is some sample code to populate the needed files

```php
<?php
// replace with the path of the autoload.php
include '/path/to/autoload.php';

// change the filenames below as you see fit.
// the files will be created in the directory of this script
// with this code
$tokenmap = __DIR__ . '/tokenmap.json';
$apimap = __DIR__ . '/apiclient.json';
$classmap = __DIR__ . '/classmap.json';

// replace with your API client name and secret:
$client = 'whatever-you-called-it';
$secret = 'FijroE@3rHOIdVERYLONGRANDOMTHING';

// passing in true creates the files if they don't exist.
$tokenmanager = new Chiara\AuthManager\File($tokenmap, $apimap, $classmap, true);
$tokenmanager->saveAPIClient($client, $secret);

// if you have just a few tokens, use this code:
// the first parameter is the app_id, the second is the app token
$tokenmanager->saveToken(12345, 'hjr2#Ohir4OIhLONGRANDOMTOKEN');
// if you have created a custom class that extends Chiara\PodioItem
// $tokenmanager->mapAppToClass(12345, 'MyCustomClass');
?>
```

Once you have created the basic client secret, you can then utilize the full
infrastructure to generate custom classes, app to token mapping, and app to
class mapping with a script like this one:

```php
<?php
// replace with the path of the autoload.php
include '/path/to/autoload.php';
$tokenmap = __DIR__ . '/tokenmap.json';
$apimap = __DIR__ . '/apiclient.json';
$classmap = __DIR__ . '/classmap.json';

$tokenmanager = new Chiara\AuthManager\File($tokenmap, $apimap, $classmap, true);
Chiara\AuthManager::setTokenManager($tokenmanager);
// if you are running this as a command-line script like:
// php maketokens.php
Chiara\AuthManager::attemptPasswordLogin();
// if you are running it in a web server:
//Chiara\AuthManager::attemptServerLogin('http://localhost' . $_SERVER['PHP_SELF'], isset($_GET['logout']),
//                                       isset($_GET['code']) ? $_GET['code'] : false);
// NOTE: if you run this in a web server, you need to set the permissions of
// the directory you wish to save your custom classes to 0777 or the web
// server will not be able to write the files out

$myorganizations = Chiara\PodioOrganization::mine();
// replace "my_org_url_label" with the organization url label.
// replace "Workspace name" with the name of the workspace you want to generate classes for
foreach ($myorganizations['my_org_url_label']->workspaces->matching('Workspace name') as $space) {
    $ret = $space->generateClasses(__DIR__ . '/models');
}
?>

```

This code will save your API tokens and map classes.  Note that by default,
API clients do not have access to app tokens, this is an additional level that
must be requested from Podio directly through a support ticket.  If you do not
want to request that additional access, then you will need to manually save each
token.

##Using authentication in a real-world script

Once your authentication is all set up, there are a couple of ways to use it.
In a web hook script, you will want to have this preface code:

```php
<?php
// replace with the path of the autoload.php
include '/path/to/autoload.php';
$tokenmap = __DIR__ . '/tokenmap.json';
$apimap = __DIR__ . '/apiclient.json';
$classmap = __DIR__ . '/classmap.json';

$tokenmanager = new Chiara\AuthManager\File($tokenmap, $apimap, $classmap, true);
Chiara\AuthManager::setTokenManager($tokenmanager);
// use app authentication
// if this line is not present, user authentication is assumed
Chiara\AuthManager::setAuthMode(Chiara\AuthManager::APP);

// now do your stuff here
?>
```

The library will throw an Exception if any attempt is made to access an API
call that cannot be called in app authentication mode.  An example would be
retrieving a space contact's information.