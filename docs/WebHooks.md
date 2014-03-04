ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Web Hooks

The most important way to add a missing feature to a Podio application in
day-to-day use is via web hooks.  This library makes creating and handling
web hooks extremely simple, and is a primary focus of the additional value
added to the existing Podio API.

##Handling a web hook

Web hooks can be handled quite easily.  The Chiara\HookServer class is designed
to handle the work of processing an incoming hook, and so creating a hook
responder is as straightforward as this example:

```php
<?php
include dirname(__DIR__) . '/autoload.php';

// see the help for authentication to understand these lines
$tokenmanager = new Chiara\AuthManager\File(__DIR__ . '/localtokens.json', __DIR__ . '/mylocaltest.json', true);
Chiara\AuthManager::setTokenManager($tokenmanager);

try {

    $item = new Chiara\PodioApp(array('app_id' => 5), false);
    $item->on['item.create'] = function($post, $params) {
        // do simple processing such as retrieving the item
        // and combining two fields
        $item = new Chiara\PodioItem($post['item_id']);
        $item->fields['complextext'] = $item->fields['simpletext'] . ' ' . $item->fields['somenumber'];
        $item->save();
    };

    Chiara\HookServer::$hookserver->perform();

}
catch (PodioError $e) {
  Podio::$logger->log($e->body['error_description']);
  // Something went wrong. Examine $e->body['error_description'] for a description of the error.
}

```

The callback for a web hook is passed 2 arrays.  The first is the contents of $_POST,
and the second is any information passed in the url itself.  This allows creation of
hooks that have static parameters so that you can re-use code.  This URL:

> http://example.com/path/to/hook.php/dosomething/12345

Can be used to inform the hook to perform action "dosomething" with parameter "12345"

    $item->on->dosomething['item.create'] = function($post, $params) {
        // $params is array("12345")
    }

This is very useful for data layouts where a number of duplicate workspaces are
set up, and you want the same web hook for each one.  For this, you could pass
in the app ID for the specific application you want to access

    $item->on->dosomething['item.create'] = function($post, $params) {
        $item = new Chiara\PodioItem;
        $item->app_id = $params[0];
        $item->id = $post['item_id'];
        $item->retrieve();
        // now do something with $item
        $dosomething = $item->fields['whatever'];
    }

###Custom classes and web hooks

Generating a custom class as documented [here](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/ApplicationStructure.md)
is another way to handle web hooks.  As such, this example class can be used directly:

```php
<?php
class MyItem extends \Chiara\PodioItem
{
    protected $MYAPPID=5;
    function __construct($info = null, $retrieve = true)
    {
        parent::__construct($info, new Structure\MyItem, $retrieve);
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
?>
```

The same code demonstrated above can be handled as:

```php
    function onItemCreate($params)
    {
        // id is already set
        $this->app_id = $params[0];
        $dosomething = $this->fields['whatever'];
    }
```

setting the webhook becomes as simple as:

```php
$item->on->dosomething['item.create'] = $item;
```

##Creating a web hook

Adding a hook on the server is also very easy.  Hooks can be created directly
from a Chiara\PodioApp or Chiara\PodioItem instance, Chiara\PodioWorkspace instance, or
Chiara\PodioApp\Field or Chiara\PodioItem\Field instance to add a hook on an app field.  These hooks
will only be triggered upon a modification to a single field.

Here are the simple examples:

```php
// this tells the hook server that the url we will construct all other URLs from
// is as follows.  You must call this method before creating hooks
Chiara\HookServer::$hookserver->setBaseUrl('http://example.com/hook.php');

// Note: app_id must be set!
$item = new Chiara\PodioItem;
$item->app_id = 5;
// makes a hook for app 5 with url "http://example.com/hook.php",
// type "item.create", ref_type "app"
$item->hook['item.create']->create();
// makes a hook for app 5 with url "http://example.com/hook.php/dosomething",
// type "item.update", ref_type "app"
$item->hook->dosomething['item.update']->create();

// makes a hook for field with external_id "whatever", with url "http://example.com/hook.php",
// type "item.update", ref_type "app_field"
$item->fields['whatever']->hook['item.update']->create();

$space = new Chiara\PodioWorkspace(5);
// makes a hook for workspace 5 with url_label "foobar", with url "http://example.com/hook.php/param/12345"
// type "app.create", ref_type "space"
$space->hook->param->{"12345"}['app.create']->create();
```

Note that you can pass in any value by enclosing it with curly brackets and double quotes.

To access the parameter, you would simply access the hook in hook.php as follows:

```php
$space = new Chiara\PodioWorkspace();
$space->on->param['app.create'] = function($post, $params) {
    // $params[0] is "12345"
}
```

##Example from the real world

Here is a real-world example of using a custom class to handle web hooks:

```php
<?php
namespace SOM\Model;
class ChamberGroups extends \Chiara\PodioItem
{
    protected $MYAPPID=5;
    function __construct($info = null, $retrieve = true)
    {
        parent::__construct($info, new \SOM\Model\Structure\ChamberGroups, $retrieve);
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

    function onItemCreate($params)
    {
        $this->updateMemberActive();
        $this->updateMemberGroups();
        foreach ($this->fields['members'] as $member) {
            $member->save();
        }
    }

    function onItemUpdate($params)
    {
        $diff = $this->diff($params['revision_id'] - 1);
        if (!isset($diff['members'])) return;
        foreach ($diff['members']->added as $member) {
            $member->fields['active'] = 'Yes';
            if (!$member->inGroup($this)) {
                $members->fields['groups']->value[] = $this;
            }
            $member->save();
        }
        foreach ($diff['members']->deleted as $member) {
            unset($members->fields['groups']->value[$this->id]);
            if (!count($member->fields['groups'])) {
                $member->fields['active'] = 'No';
            }
            $member->save();
        }
    }

    function updateMemberActive()
    {
        foreach ($this->fields['members'] as $member) {
            $member->fields['active'] = 'Yes';
        }
    }

    function updateMemberGroups()
    {
        foreach ($this->fields['members'] as $member) {
            if (!$member->inGroup($this)) {
                $member->fields['groups']->value[] = $this;
            }
        }
    }
}
```

Basically, the code handles a 2-way synchronization between the members of a
chamber music group, and the chamber music groups that students belong to.
The app has a mapping of app_id to class name, and so when an app reference
for the student is accessed, the SOM\Model\Students class is automatically
instantiated to represent the item.  Thus, its fields can be accessed as in:

```php
$member->fields['groups']->value[] = $this;
```

The above line sets up the circular reference, filling in the student's
"groups" app reference field to point back to the group the student belongs to.

The same code written using the existing Podio API was 5 times as many lines of
code, much harder to read and debug.