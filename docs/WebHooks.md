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



##Creating a web hook

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