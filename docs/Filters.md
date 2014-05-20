ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Filters and Views

Podio allows retrieving a subset of the items within an application, and it
does this via filtering.  In addition, a specific filter can be saved along
with layout information as a view.  The filter API is rate-limited, so be
careful to note how much you plan to use it each hour or you will run out of
API calls very fast.

The ChiaraPodioPHP library implements this functionality as a variable named
"filter" within the Chiara\PodioApplication object.  Each filter has a view
associated with that filter.  This can be a pre-existing view, or one created
solely for the purpose of filtering the app.

For example:

```php
<?php
$app = new Chiara\PodioApp(12345);
foreach ($app->filter as $item) {
    // iterates over every item in the app
}

$newview = new Chiara\PodioView($app);
$newview->name = 'My View';
$newview->cardLayout()->groupColsBy('my_category');

// can add categories by name or by number
$newview->fields['my_category']->add('first')->add(2);
$newview->fields['number_field']->from(3)->to(40);
$newview->fields['date_field']->past(5)->days();
$newview->pseudofields['created_by']->me();
$newview->save();

foreach ($app->filter->by['My View'] as $item) {
    // ....
}

// ad hoc filtering

$app->filter->fields['progress_field']->from(50)->to(100);
$app->filter->pseudofields['created_by']->me();

foreach ($app->filter as $item) {
    // ...
}

// pass in another view

$view = new Chiara\PodioView($app, 'Some name');
foreach ($app->filter->by($view) as $item) {
    // ...
}
?>
```

##Filtering by field

###Relationship (App Reference) fields

The relationship field type can be filtered by a list of items.  Items can be
added with the `add()` method, and can be one of three types:

 1. integer representing an item_id
 2. array with 'item_id' index
 3. a Chiara\PodioItem object, or any containing object that returns an item_id
    when accessed with $obj->id

```php
<?php
$app->filter->fields['relationship']->add(4)->add(array('item_id' => 5))
    ->add(new Chiara\PodioItem(6));
?>
```

###Contact fields

Like the relationship field, contact fields can be filtered by a list of contacts.
Contacts can be added with the `add()` method and can be one of three types:

 1. integer representing a profile_id
 2. array with 'profile_id' index
 3. a Chiara\PodioContact object, or any containing object that returns a profile_id
    when accessed with $obj->id

```php
<?php
$app->filter->fields['contact']->add(4)->add(array('profile_id' => 5))
    ->add(new Chiara\PodioContact(6));
?>
```

###Number, Money, Calculation fields

Number-based fields accept a range of possible values.  Money fields do not
distinguish between different currencies when filtering, and only consider the
value as a float.

```php
<?php
$app->filter->fields['number']->from(4)->to(20.4);
$app->filter->fields['money']->from(3.50)->to(23.49);
$app->filter->fields['calculation']->from(-43)->to(42);
?>
```

If one wants to ensure that passed in values represent actual values within the
application, use the `verifyPossible()` method

```php
<?php
$app->filter->fields['number']->verifyPossible()->from(4)->to(7);
?>
```

An exception will be thrown if either the from value or to value is outside
the range of values represented by existing items within the app.

###Progress fields

Progress fields work like number fields, except they will only accept values
between 0 and 100.  Floats are rounded to the integer they represent.

```php
<?php
$app->filter->fields['progress']->from(3)->to(100);
?>
```

###Duration fields

Duration fields accept 2 possible values:

 1. a duration in seconds
 2. a time string representing a duration.

Like other number fields, the filter accepts a range of values

```php
<?php
$app->filter->fields['duration']->from(23)->to('16 hours');
?>
```

###Date fields

Date fields accept two different kinds of values:

 1. a date string representing a time
 2. a relative date string.

To pass in absolute times, use the `from()` and `to()` methods to represent a
starting date and ending date to filter by

```php
$app->filter->fields['date']->from('2003-04-13')->to('next Tuesday');
```

To pass in relative times, use the `past()` and `future()` methods with one of
4 helper methods: `days()`, `weeks()`, `months()` or `years()`

```php
<?php
$app->filter->fields['date']->past(6)->days()->future(20)->years();
?>
```

Relative and absolute values may be mixed:

```php
<?php
$app->filter->fields['date']->past(6)->days()->to('tomorrow');
?>
```

By default, relative dates are rounded.  To disable this behavior, use the
`notRounded()` method

```php
<?php
$app->filter->fields['date']->past(6)->days()->notRounded();
?>
```

You may use just one of the bounds, and the other bound will be assumed to be
now.

###Category and Question fields

Category fields are filtered by selecting specific options using the `add()`
method.  This method accepts 3 possible values:

 1. an integer representing the option id
 2. a string representing the option's text
 3. a Chiara\PodioItem\Values\Option object representing an option.

```php
<?php
$app->filter->fields['category']->add(3)->add('Option text')
    ->add($item->fields['category']->value);
?>
```