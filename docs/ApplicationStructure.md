ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Working with Application Structure

Most of the ChiaraPodioPHP library's added functionality is made possible
through extra meta-data called Application Structure.  The meta-data
contained in the application structure is used to  make it possible to
handle the data contained in items more intelligently.

For example:

```php
<?php
$item = new Chiara\PodioItem;
$item->fields['fieldname'] = 5;
?>
```

Without the Application Structure meta-data, there are 3 unknowns in the
above code:

 1 Which field is "fieldname"?
 2 What type of field is "fieldname"?
 3 What kind of data is "5"?  Is it text, number, progress, or duration?

There are a couple of ways to generate Application Structure meta-data:

 1 manually
 2 auto-generate from a retrieved item or app
 3 pre-generate using the generated class feature

###Manually generating application structure

This is most useful for an item containing a deleted application field, as it
is a tedious process compared to the other two options.

Here is sample code showing how to manually generate application structure

```php
<?php
include '/path/to/autoload.php';

$structure = new Chiara\PodioApplicationStructure;

// 'external_id' is the field's external id, 54321 is the field_id from the
// application definition
$structure->addTextField('external_id', 54321);

// add a field with additional meta-data

// in this case, referenceable types
$structure->addAppField('external_id', 54321, array(65432, 76543));
?>
```

Full code reference is in the [source code starting at line 50](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioApplicationStructure.php#L50)

###Auto-generate from retrieved item or application

This is by far the simplest option, and should be used for items that you are
only going to read in a simple application.  To auto-generate Application
Structure meta-data, simply access any field, and the library will attempt
to auto-generate the meta-data.  If the item has not been retrieved from the
podio server, it will do this automatically as well, based on its item_id, and
will also automatically authenticate if in app authentication mode using the
item's app_id.

For example:

```php
<?php
include '/path/to/autoload.php';

$item = new Chiara\PodioItem(array('item_id' => 12345, 'app' => array('app_id' => 5)));

// automatically retrieve the meta-data for the item
$value = $item->fields['fieldname'];
?>
```

Generating from an application is also simple:

```php
<?php
include '/path/to/autoload.php';

// retrieve the application data for the application with app_id 6
$app = new Chiara\PodioApp(6);

$structure = Chiara\PodioApplicationStructure::structureFromApp($app);
$item = new Chiara\PodioItem(null, $structure);

// intelligently validate and set up so we can save the item
$item->fields['fieldname'] = 6;
$item->fields['date'] = '2014-03-01';
$item->save();
?>
```

This is the preferred way to set up a simple application where you will be
creating items, as it is the only way to get the meta-data into a new
item.  It should also be used when adding fields to an item, as the definition
of an item will not contain any fields that do not exist in the item.

###pre-generate using the generated class feature

This is the most powerful feature, and can be used very easily.  The
library has the capability to read workspaces from an organization and
generate both structure meta-data classes and custom classes that represent
items within an application.

Here is an actual example of a file in use in a project at the University
of Nebraska-Lincoln to set up custom classes for managing web hooks:

```php
<?php
include __DIR__ . '/autoload.php';

// format {"client":"your-client-id","token":"your api token"}
$config = json_decode(file_get_contents(__DIR__ . '/mylocaltest.json'), 1);

$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/localtokens.json',
                                            __DIR__ . '/mylocaltest.json',
                                            __DIR__ . '/map.json',
                                            true);
Chiara\AuthManager::setTokenManager($tokenmanager);
Chiara\AuthManager::attemptPasswordLogin();

// we can do this using a web page, but there are permissions issues to work out
// so the above line uses the command line to retrieve user name and password
// so that no sensitive information is stored
//Chiara\AuthManager::attemptServerLogin('http://localhost' . $_SERVER['PHP_SELF'], isset($_GET['logout']),
//                                       isset($_GET['code']) ? $_GET['code'] : false);

// This example shows how helper classes can be generated for a specific subset of workspaces
// in 3 lines of code
$myorganizations = Chiara\PodioOrganization::mine();
foreach ($myorganizations['unledu']->workspaces->matching('^SOM: Chamber Music') as $space) {
    $ret = $space->generateClasses(__DIR__ . '/SOM/Model', 'SOM\Model');
    echo $space, " processed<br>\n";
}
```

The above example also has authentication code which can be ignored for the
purposes of our work.  The only thing to note is that in order to generate
classes from more than a single application, the authentication mode cannot
be app authentication, but must be password or server authentication.

The important code begins with these lines:

```php
<?php
$myorganizations = Chiara\PodioOrganization::mine();
foreach ($myorganizations['unledu']->workspaces->matching('^SOM: Chamber Music') as $space) {
    $ret = $space->generateClasses(__DIR__ . '/SOM/Model', 'SOM\Model');
    echo $space, " processed<br>\n";
}
```

First, we retrieve a list of all the organizations we belong to.  Next, we
access the specific organization for unl.edu (url_label "unledu").  We retrieve
a list of workspaces that match the regular expression '^SOM: Chamber Music.'

Finally, with this line of code:

```php
<?php
    $ret = $space->generateClasses(__DIR__ . '/SOM/Model', 'SOM\Model');
```

We are generating classes with the same name as their application (spaces stripped,
CamelCase names for each word), and save them into the 'SOM/Model' directory,
and put them into the 'SOM\Model' namespace.

Here is what one of the generated structure classes looks like:

```php
<?php
namespace SOM\Model\Structure;
class Instruments extends \Chiara\PodioApplicationStructure
{
    const APPNAME = "3/4";
    protected $structure = array (
      50180296 => 
      array (
        'type' => 'text',
        'name' => 50180296,
        'id' => 'name',
        'config' => NULL,
      ),
      'name' => 
      array (
        'type' => 'text',
        'name' => 50180296,
        'id' => 'name',
        'config' => NULL,
      ),
    );
}

```

The space_id/app_id have been changed to 3 and 4.

And finally, the generated class:

```php
<?php
namespace SOM\Model;
class Instruments extends \Chiara\PodioItem
{
    protected $MYAPPID=4;
    function __construct($info = null, $retrieve = true)
    {
        parent::__construct($info, new \SOM\Model\Structure\Instruments, $retrieve);
    }

    /**
     * handle an item.create hook in here
     * @param array $params any url-specific parameters passed in to
     *                       differentiate between hooks.  The item is already set up
     *                       and can be used immediately.
     */
    /*
    function onItemCreate($params)
    function onItemUpdate($params)
    function onItemDelete($params)
    function onCommentCreate($params)
    function onCommentDelete($params)
    function onFileChange($params)
    {
    }
    */
}
```

