ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Working with Items

Items are the most commonly used feature of Podio, and this is where the most
important data lives.  As such, it is very important that it be easy to work
with this data.  This is the primary goal of the ChiaraPodioPHP library.

Using other features such as the meta-data provided by [Chiara\PodioApplicationStructure](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/ApplicationStructure.md),
the ChiaraPodioPHP library makes it possible to access data inside item fields
using intuitive PHP values for representing the data.  Complex types like
app references are handled as if they were arrays of objects, and make it
incredibly simple to manage complexity.  External references from app
reference fields in other applications are also handled as if they were
arrays of objects.

Retrieval of remote resources is handled transparently without the need to
intervene in the code itself, reducing lines of code that need to be written
to solve problems.

##Item Fields

Each item field can be accessed from a virtual array contained in the
"fields" member of a Chiara\PodioItem object.  Fields may be accessed by
their field_id, external_id, or by the order that they occur in the application:

```php
<?php
$item = new Chiara\PodioItem(12345);

// field_id
$item->fields[654321];
// external_id
$item->fields['fieldname'];
// order
$item->fields[0];
?>
```

Note that a field is retrieved as an object so that you can access meta-data
information about that field.  To get the value of a field, you must explicitly
reference the value as such:

```php
<?php
$value = $item->fields['fieldname']->value;
?>
```

###Item Field Types

Each field type is represented differently

###Simple Types

####Text

The text field is represented as a string

```php
<?php
$item->fields['textfield'] = 'Hello world!';
$text = $item->fields['textfield']->value;
?>
```

####Number

Number fields are represented as floats.  Any non-numeric value will throw
an exception if passed in as a value.

```php
<?php
$item->fields['numberfield'] = 5; // converts to 5.0
$item->fields['numberfield'] = '5.2345'; // converts to a float 5.2345
$item->fields['numberfield'] = 5.243;
$value = $item->fields['numberfield']->value;

// throws an exception
$item->fields['numberfield'] = 'oops';
?>
```

####Progress

Progress fields are represented as an integer between 0 and 100.  An
exception is thrown if any other value is passed in.

```php
<?php
$item->fields['progressfield'] = 65;
$value = $item->fields['progressfield']->value;

// throws an exception
$item->fields['progressfield'] = -1;
?>
```

####Duration

Duration fields are represented as an integer duration in seconds.  Any time
duration string, DateInterval object, or integer value may be passed in.

```php
<?php
$item->fields['durationfield'] = 60; // 1 minute
$item->fields['durationfield'] = new DateInterval('PT1H') // 1 hour
$item->fields['durationfield'] = '60 seconds';

$value = $item->fields['durationfield']->value; // will always be an integer

// throws an exception
$item->fields['durationfield'] = new stdClass;
?>
```

####Money

Money fields are represented as an array containing indices "currency" and
"value."  Money fields can be passed in an integer value, which will assume
USD as the currency, any currency string, or an array with the indices
"currency" and "value."  A currency string is a natural language currency
string such as "$253.45" or "254.543,23 Û" or "´423 543,54."

```php
<?php
$item->fields['moneyfield'] = 60 // assumes 60 US dollars
$item->fields['moneyfield'] = "254.543,23 Û" // converts to 253543.23 Euros
$item->fields['moneyfield'] = array('currency' => 'DKK', 'value' => 50);

$value = $item->fields['moneyfield']->value;
?>
```

###Complex Types

####Date

Date fields are represented using a DatePeriod object.  The start date/time
and end date/time can be retrieved using a simple foreach loop.  Values can be
a wide variety of acceptable date representations.

 1. a date string such as "Feb. 3, 2013" "tomorrow" "+4 weeks"
 2. a DateTime object
 3. a DatePeriod object
 4. a Unix timestamp
 5. an array with indices "start" and "end" in format "Y-m-d H:i:s" as defined
    in the documentation for http://php.net/date
 6. a [Chiara\PodioItem\Values\Date](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem/Values/Date.php) object,
    which can be retrieved as a field value from an item's field.

```php
<?php
$item->fields['datefield'] = 'tomorrow';
$item->fields['datefield'] = new DateTime(); // will set to now
$item->fields['datefield'] = new DatePeriod(new DateTime('2013-03-01'), new DateTime('2013-03-02'), new DateTime('2013-03-03'));
$item->fields['datefield'] = 1245346;
$item->fields['datefield'] = array('start' => '2013-03-01 12:34:45', 'end' => '2013-03-01 13:34:45');
$item->fields['datefield'] = $anotheritem->fields['date']->value;

// note that the duration of a date field can be retrieved easily:

$duration = $item->fields['datefield']->duration; // calculated on the fly from the value
?>
```

One caveat: if you are using a DatePeriod, be aware that the 3rd parameter must be a
date slightly in the future of the end date.  It is much simpler to use an array.

###Fields that can contain multiple values

Several fields can contain more than 1 value, such as the location field, which
can contain multiple addresses.  Every one of these implements a collection
that allows accessing the values by the order in which they appear, by id (if any)
or by some other logical identifier.

####Location

Location fields are stored as a text address.  You can either set a location
to a text value, an array of text values, or add a text value to an array, or
as a collection of locations from another item.

```php
<?php
$item->fields['location'] = '66 Lincoln Center Plaza, New York, NY 10023';
$item->fields['location'][] = '66 Lincoln Center Plaza, New York, NY 10023';
$item->fields['location'] = array('66 Lincoln Center Plaza, New York, NY 10023',
                                  '22 Foo St., Fake City, KS 12345'
                            );
$item->fields['location'] = $anotheritem->fields['mylocation']->value;
?>
```

The only way to reference location values is by index or through an iteration loop

```php
<?php
if (isset($item->fields['location']->value[0])) {
    $value = $item->fields['location']->value[0];
}

foreach ($item->fields['location'] as $location) {
    $value = $location->value;
}
?>
```

####Question and Category

For question field and category fields that only allow 1 option, the representation
is the same.  You can set a value to the id of an option, the text of an option,
to an array containing the index "id", or a Chiara\PodioItem\Values\Option object.

```php
<?php
$item->fields['questionfield'] = 1; // set to the option with id "1"
$item->fields['questionfield'] = 'hello'; // set to the option with text "hello"
// set to the result returned via a Podio API call from the Podio PHP library
$item->fields['questionfield'] = array('id' => 1, 'text' => 'hello', 'color' => '#FCDEFC');
$item->fields['questionfield'] = $anotheritem->fields['questionfield']->value;
?>
```

For category fields that allow multiple values, the value returned is a collection
of [Chiara\PodioItem\Values\Option](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem/Values/Option.php)
objects.  You can reference the selected objects by id, or by a foreach loop.

```php
<?php
if (isset($item->fields['multiplecategory']->value[1])) {
    $value = $item->fields['multiplecategory']->value[1]; // get category field
}

foreach ($item->fields['multiplcategory'] as $value) {
    $val = $value->text;
}
?>
```

####Embed

Embed fields are represented as a collection of
[Chiara\PodioItem\Values\Embed](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem/Values/Embed.php)
objects.  You can set an embed to either a URL to embed, an embed_id, the array
returned from a Podio API call to retrieve an embed, or another field's embed
contents.

```php
<?php
$item->fields['embedfield'] = 'http://example.com';
$item->fields['embedfield'] = 123456; // set to embed with embed_id of 123456
$item->fields['embedfield'] = array('embed' => array('embed_id' => 1), 'file' => array('file_id' => 3));
$item->fields['embedfield'] = $anotheritem->fields['embedfield']->value;
$item->fields['embedfield'] = $anotheritem->fields['embedfield']->value[123456]; // set to the value of a single embed in a collection

foreach ($item->fields['embedfield'] as $embed) {
    $link = $embed->original_url;
}
?>
```

####Image

Image fields are represented as a collection of
[Chiara\PodioItem\Values\Image](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem/Values/Image.php)
objects.  You can set an image to either a URL to add as an image, or a file_id of
an uploaded image.

```php
<?php
$item->fields['imagefield'] = 'http://example.com/img.jpg';
$item->fields['imagefield'] = 123456; // set to an uploaded file with file_id of 123456

foreach ($item->fields['imagefield']->value as $image) {
    $thumbnail = $image->thumbnail;
}
?>
```

####Contact

Podio represents two very different data objects with the same data structure.
Podio contacts are people with direct access to a workspace, whose email
addresses have been added as a member to the workspace.  Space contacts are
simply a directory of contact information, and can be anyone, regardless of
whether they are even aware of Podio's existence.  The crucial distinguishing
factor is whether the contact has a user_id.  Only Podio contacts have a
user_id.  In addition, space contacts have a space_id set, and Podio contacts do
not.

In the ChiaraPodioPHP library, we represent both contact types using the same
object, a [Chiara\PodioContact](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioContact.php)
object.

When referencing a contact field in an item, you can determine which kinds of
contacts are valid through the contact_type variable:

```php
if ($item->fields['contact']->contact_type === 'space') {
    // space contact
} else {
    // podio contact
}
```

A contact field can be set to these values:
 1. an integer value, which is interpreted as a profile_id (all contacts, both
    space and podio, have a profile_id)
 2. an array of integers or an array of contact objects
 3. another contact field's value
 4. any contact object

```php
<?php
$item->fields['contact'] = 13245; // interpreted as a profile_id
$item->fields['contact'] = array(1325, 453254);
$item->fields['contact'] = new Chiara\PodioContact(12345);
$item->fields['contact'] = $anotheritem->fields['contact2']->value;
```

Contacts can be iterated over as well

```php
<?php
foreach ($item->fields['contact'] as $contact) {
    // do something
}
$contact = $item->fields['contact']->value[13245]; // indexed by profile_id
$contact = $item->fields['contact']->value[0]; // indexed by order
?>
```

####App Reference

In the ChiaraPodioPHP library, app references are represented as a collection of
[Chiara\PodioItem](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem.php)
objects, encapsulated within an app value.

App references are very powerful.  The Chiara PodioPHP library allows direct
access to a referenced object's field without further programming.

You can set an app reference field to these values:
 1. an integer item_id
 2. an array of integers or an array of item objects
 3. another app reference field's value
 4. any item object

```php
<?php
$item->fields['app-reference'] = 13245; // interpreted as app_id
$item->fields['app-reference'] = array(13452, 1354356);
$item->fields['app-reference'] = new Chiara\PodioItem(123454);
$item->fields['app-reference'] = $anotheritem->fields['app2']->value;

foreach ($item->fields['app-reference']->value as $app) {
    // do something
}
$item = $item->fields['app-reference']->value[13245]; // indexed by app_id
$item = $item->fields['app-reference']->value[0]; // indexed by order

// access a field within a referenced item
$info = $item->fields['app-reference']->value[13245]->fields['text']->value;
?>
```

App references can be iterated over as well

```php
<?php
foreach ($item->fields['app-reference'] as $ref) {
    // $ref is a Chiara\Podio\Value\App object
    // it can be accessed as if it were a Chiara\PodioItem object
    $ref->fields['parent-ref'] = $item;
    // access the internal Chiara\PodioItem object directly
    $obj = $ref->getValue();
}
?>
```

##References

Often, an item will need to retrieve an external reference from another application's
app reference field.  The ChiaraPodioPHP library makes this very simple.

The references member is indexed by both app_id and the app url_label, making it
very simple to determine if an app reference exists and access it.  Items can be
accessed by the order in which they occur, by a known item_id, or by the app_item_id
within the referencing app itself, using the "items" member.

```php
<?php
if (isset($item->references['appname'])) {
    // retrieve information from the first reference
    $info = $item->references['appname'][0]->retrieve()->fields['manageritem']->value;
    // easy setting of circular references
    $item->references['appname'][0]->retrieve()->fields['manageritem'] = $item;

    // retrieve information from a known item_id that references this one
    $info = $item->references['appname'][12345]->retrieve()->fields['text']->value;
}

// via app_id instead of url_label
if (isset($item->references[123456])) {
    // access a reference by app_item_id
    $mynumber = $item->references[123456]->items[1]->retrieve()->fields['number']->value;
}
?>
```

##Revisions

One of the most important features of Podio items is item revisions.  This
feature can be used to determine exactly what the user changed from one revision
to the next, and is especially useful in an item.update web hook.  The
ChiaraPodioPHP library implements revisions as if they were an array within the
item itself.

Revision differences are retrieved using the [diff()](https://github.com/cellog/ChiaraPodioPHP/blob/master/Chiara/PodioItem.php#L220)
method.  This is used to retrieve differences from the current revision to a
previous revision.  For example, in a web hook, one might:

```php
<?php
    function onItemUpdate($params)
    {
        // retrieve changes since the last update
        $diff = $this->diff($params['revision_id'] - 1);
    }
?>
```

The diff acts as an array indexed by field_id, external_id, and order, so
the presence of modifications to a specific field can be easily tested for:

```php
if (isset($diff['fieldname'])) {
    // ...
}
```

Once a field's presence is determined, the old and new values can be accessed
using members "to" and "from":

```php
$oldvalue = $diff['fieldname']->from->value;
$newvalue = $diff['fieldname']->to->value;
// this is the same as "to"
$newvalue = $diff['fieldname']->value;
```

For fields that are collections of values, such as an app reference, two
special arrays are also available, "added" and "deleted."  These arrays
represent the values that were added to the collection or removed from the
collection.

```php
foreach ($diff['fieldname']->added as $item) {
    // work with the item values
}
if (count($diff['fieldname']->deleted)) {
    // remove deleted items from some other location referencing them
}
```

##Special Item Features

Items track changes, and only save changes from when they are retrieved:

```php
<?php
// retrieve an item from the server
$item = new Chiara\PodioItem(12345);

// no API call is made, nothing was changed.
$item->save();

// assume that the value of field "blah" is 6, and "blah" is a number field
$item->fields['blah'] = 6;

// no changes made, no API call
$item->save();

// force the API call
$item->save(true);

// now we have made a change
$item->fields['blah'] = 7;

// a call to the server is made with the changes;
$item->save();
?>
```

All items and fields can be converted to a human-readable string:

```php
// retrieve an item from the server
$item = new Chiara\PodioItem(12345);

echo $item; // prints the title of the item

echo $item->fields['numberfield']; // prints the number value of the number field
echo $item->fields['datefield']; // prints "start => end" with the start date and end date
// and so on

// thus it is easy to combine fields for other fields:

$item->fields['detail'] = '(' . $item->fields['category'] . ') ' . $item->fields['summary'] . ' - ' . $item->fields['priority'];
```

A Chiara\PodioApp object can be retrieved directly for any item:

```php
// retrieve the application for an item
$app = $item->app;
// create a web hook listener
$app->on['item.create'] = function($post, $params) {
   // ... do something
};
```

If you have [generated custom classes](https://github.com/cellog/ChiaraPodioPHP/blob/master/docs/ApplicationStructure.md#pre-generate-using-the-generated-class-feature)
and wish to have them automatically used, use the static factory method:

```php
$item = Chiara\PodioItem::factory(array('item_id' => 12345, 'app' => array('app_id' => 23456)));
```