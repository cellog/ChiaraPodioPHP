ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

This library is designed to simplify some of the annoying tasks that must be repeated for each
podio API task.  It is useful both for web hooks and for more general-purpose Podio
applications.  To begin, it focuses primarily on interacting with items and applications.
There is extensive built-in support for application-based authentication and web hooks, basic
support for handling organizations and drilling down through to workspaces and apps within them.
All of this code is designed to support the interaction with items, but future plans include adding
ancillary API code such as tasks and messages.

The library uses the Podio class heavily for the actual authentication to Podio and requests
to the API, and is fully compatible with the existing API, so you can mix and match if this
library does not fully do what you need.

Documentation is in the [docs](https://github.com/cellog/ChiaraPodioPHP/tree/master/docs) directory

#Working with Web Hooks
This is the primary focus of the library at this stage, and support for web hooks, working with items
and apps is fully implemented.
> [web hooks](https://github.com/cellog/ChiaraPodioPHP/tree/master/docs/WebHooks.md)
> * [handling a web hook](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/WebHooks.md#handling-a-web-hook)
> * [creating a web hook](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/WebHooks.md#creating-a-web-hook)

#Authentication

> [authentication](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Authentication.md)

#Working with Applications

> [application structure](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/ApplicationStructure.md)

#Working with Items

> [items and fields and their values](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Items.md)
> * [item references](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Items.md#references)
> * [item revisions](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Items.md#revisions)
> * [special features](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Items.md#special-item-features)

#Working with Search

> [searching podio](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Search.md)
> * [global search](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Search.md#global)
> * [organization search](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Search.md#organization)
> * [workspace search](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Search.md#workspace)
> * [app search](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/Search.md#app)

#Unimplemented features
 - tasks (partially implemented, undocumented)
 - statuses (partially implemented, undocumented)
 - actions
 - app market
 - most application features
 - batches
 - calendar
 - comments
 - most contact features
 - conversations
 - adding an embed
 - uploading a file
 - web forms
 - grants
 - importer
 - integrations
 - item features
   * Get references to item by field
   * Bulk delete items (will be implemented as app method)
   * Delete item reference
   * Export items (will be implemented as app method)
   * Filter Items (and by view) (will be implemented as app method)
   * Find referenceable items
   * Get item preview for field reference
   * Get item count (will be implemented as app method)
   * Get items (will not be implemented)
   * Get items as Xlsx (will not be implemented)
   * Set participation
   * Update item reference
 - linked accounts
 - notifications and subscriptions
 - most organization features
 - status questions (implemented, undocumented)
 - ratings
 - most space features
 - tags
 - views
 - widgets

Here is some sample code showing how it works:

```php
<?php
include 'autoload.php'; // now you can use any class from Podio or from ChiaraPodio

// authentication via server (webpage)
$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/localtokens.json', __DIR__ . '/mylocaltest.json', true);
Chiara\AuthManager::setTokenManager($tokenmanager);
// the first parameter is the address podio should redirect to upon success
// the next is a true/false that is used to determine whether the user has attempted to log out
// the final is either the code returned from podio's OAuth, or false if none
Chiara\AuthManager::attemptServerLogin('http://localhost' . $_SERVER['PHP_SELF'], isset($_GET['logout']),
                                       isset($_GET['code']) ? $_GET['code'] : false);
// if you plan to do any work with organizations or workspaces as a whole, or contacts,
// you must use this authentication model.

// This example shows how helper classes can be generated for a specific subset of workspaces
// in 3 lines of code
$myorganizations = Chiara\PodioOrganization::mine();

// the matching() method takes a regular expression with optional starting/ending ^ and $
foreach ($myorganizations['unledu']->workspaces->matching('^SOM: Chamber Music') as $space) {
    $ret = $space->generateClasses(__DIR__ . '/modeltest', 'SOM\Models');
    echo $space, " processed<br>\n";
}

// *********************************** web hook API examples **************************************** //

// authentication via app (this is what you will use in web hooks)
// token managers allow you to keep your app tokens out of your public source code
// to avoid the security risk
$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/tokens.json', __DIR__ . '/api.json', true);

// authentication modes are app and user.
// app authentication automatically switches context to the app you are
// requesting information from, so you can use this to retrieve a value from
// an app reference and the library silently switches context in the background
// as needed
Chiara\AuthManager::setAuthMode(Chiara\AuthManager::APP);
Chiara\AuthManager::setTokenManager($tokenmanager);

// create items for specific applications based on the application ID
class ThisApp extends Chiara\PodioItem
{
    protected $MYAPPID = 15436;
}

class AnotherApp extends Chiara\PodioItem
{
    protected $MYAPPID = 26463;
}

$item = new ThisApp(array(12345); // retrieve the item with ID 12345
$item2 = new AnotherApp(25346); // retrieve the item with ID 12345

// simple access to change a field and save it by its external ID
$item->fields['app-reference'] = 23456;
// or by its field id
$item->fields[4582763485] = 23456;

// you can pass any logical value in
$item->fields['app-reference'] = $item2;
// or minimally, the json_decoded output of another API call as long as the item_id field is set
$item->fields['app-reference'] = array('item_id' => 152423);

// No hassle, no mess.  Only changed fields are updated
$item->save();
// Or, force all the fields to be explicitly saved
$item->save(true);

// easy access to app reference values
$item->fields['managercontact'] = $item->fields['app-reference']->value->fields['ownercontact']->value;

// easy access to references to this item
$item2->references['appname'][0]->retrieve()->fields['manageritem'] = $item;
// access a specific known item by its item_id
$item2->references['appname'][12345]->retrieve()->fields['manageritem'] = $item;
// access reference by app_id instead of url_label
$item2->references[65431][0]->retrieve()->fields['manageritem'] = $item;
// access reference by known app_item_id
$item2->references['appname']->items[1]->retrieve()->fields['manageritem'] = $item;

// easy iteration
foreach ($item->fields['managercontact'] as $contact) {
    echo $contact->name;
}

// date objects can be managed as built-in DateTime objects

echo $item->fields['datefield']->value->format('Y-m-d');

$item->fields['datefield'] = '2014-02-06';
$item->fields['datefield'] = array('start' => '2014-02-06 12:43', 'end' => '2014-03-06');
$item->fields['duration'] = $item->fields['datefield']->getDuration();

// currency can be parsed directly from text:

$item->fields['currency'] = '$45.30';
// any locale is parseable
$item->fields['currency'] = '45.345,30 €';
$item->fields['currency'] = '₭ 45 345/30';

// or an array
$item->fields['currency'] = array('currency' => 'MNT', 'value' => '24353.43');

// or another currency field

$item->fields['currency'] = $item2->fields['currency']->value;

// generate helper classes for your items:

// this will create a class named "MyApp" in the "MyNamespace" namespace, expecting
// a structure class as MyStructure object in the constructor.  More about structure below
// arguments are class name, app id, structure class name, namespace name, array of interfaces to implement,
// and the path to the file to save it in
echo $item->generateClass('MyApp', 54321, 'MyStructure', 'MyNamespace', array(), __DIR__ . '/MyApp.php');

// Much of the magic of this app is performed by a Chiara\PodioApplicationStructure object.
// These objects define metadata that is used to retrieve values as the correct data structure
// so that app references always retrieve a Chiara\PodioItem\Fields\App object, dates always
// retrieve a Chiara\PodioItem\Fields\Date object and so on.

// This is also essential when setting values.  A great deal of intelligence by the library
// can be achieved this way (such as the currency parsing)

// determine the structure of an app from an item.  Be aware this can include deleted fields and will
// NOT include any fields that have not been set yet
// you should use this if an item might have fields that do not exist in the application because
// they have been deleted
$structure = Chiara\PodioApplicationStructure::fromItem($item);

// you can also determine the app structure directly from the app:
// retrieve an app from its ID number.  This is better, it will include
// all of the fields including ones that have not yet been set
$app = new Chiara\PodioApp(12345);
$structure = Chiara\PodioApplicationStructure::fromApp($app);

// arguments are workspace id, app id, class name, namespace name, file name
echo $structure->generateStructureClass(12345, 54321, 'MyAppStructure', 'MyNamespace', __DIR__ . '/MyAppStructure.php';
include __DIR__ . '/MyAppStructure.php';
include __DIR__ . '/MyApp.php';

$item3 = new MyApp(54321);
$item3->fields['complex-app-reference-field'] = array($item, $item2);

// hooks can be easily created
Chiara\Hookserver::$server->setBaseUrl('http://example.com/hook.php');

// creates a hook at "http://example.com/hook.php"
Chiara\HookServer::$server->makeHook($app, null, 'item.create');
// creates a hook at "http://example.com/hook.php/suburl"
Chiara\HookServer::$server->makeHook($app, 'suburl', 'item.create');
// creates a hook at "http://example.com/hook.php/sub/url"
Chiara\HookServer::$server->makeHook($app, 'suburl/deeper', 'item.create');

Other convenient ways to make a hook:
$app = new Chiara\PodioApp($appid);

// creates a hook at "http://example.com/hook.php"
$app->hook['item.create']->create();
// creates a hook at "http://example.com/hook.php/suburl"
$app->hook->suburl['item.create']->create();
// creates a hook at "http://example.com/hook.php/suburl"
$app->hook->suburl->deeper['item.create']->create();

// The same API works for item fields and workspaces:

$app->fields['fieldname']->hook['item.create']->create();

$space = new Chiara\PodioWorkspace($spaceid);
$space->hook['app.create']->create();

// inside hook.php you should use code like:

include 'autoload.php';
include __DIR__ . '/MyAppStructure.php';
include __DIR__ . '/MyApp.php';
$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/tokens.json', __DIR__ . '/api.json', true);
Chiara\AuthManager::setAuthMode(Chiara\AuthManager::APP);
Chiara\AuthManager::setTokenManager($tokenmanager);

// hooks can then be easily handled directly from apps or from items
class MyApp2 extends MyApp
{
    // this code can also be placed directly in MyApp.php instead of extending it again
    function onItemCreate($params)
    {
        $this->fields['app-reference']->fields['foo'] = $this->fields['number']->value;
        $this->fields['app-reference']->save();
    }
}

$item = new MyApp2;
$app = $item->app;

// this automatically registers a handler for the hook at "http://example.com/hook.php"
$app->on['item.create'] = $item;

// this automatically registers a handler for the hook at "http://example.com/hook.php/suburl"
$app->on->suburl['item.create'] = $item;

// this automatically registers a handler for the hook at "http://example.com/hook.php/suburl/deeper"
$app->on->suburl->deeper['item.create'] = $item;
?>
```
