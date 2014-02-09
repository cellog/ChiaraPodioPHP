ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

This library is designed to simplify some of the annoying tasks that must be repeated for each
podio API task.  It is useful both for web hooks and for more general-purpose Podio
applications.  To begin, it focuses exclusively on interacting with items and applications,
but will expand to include workspaces, hooks, tasks, and all the other areas of Podio the API
supports.

The library uses the Podio class heavily for the actual authentication to Podio and requests
to the API, and is fully compatible with the existing API, so you can mix and match if this
library does not fully do what you need.

Here is some sample code showing how it will work:

```php
<?php
include 'autoload.php'; // now you can use any class from Podio or from ChiaraPodio

// authentication via app
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
    const MYAPPID = 15436;
}

class AnotherApp extends Chiara\PodioItem
{
    const MYAPPID = 26463;
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
?>
```
