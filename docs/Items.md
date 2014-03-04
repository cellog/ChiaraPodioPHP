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

One caveat: if you are using a DatePeriod, be aware that the 3rd parameter must be a
date slightly in the future of the end date.  It is much simpler to use an array.
```

###Fields that can contain multiple values

####Location

####Question and Category

####Embed

####Image

####Contact

####App Reference