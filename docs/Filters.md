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
$simpleview = $app->filter->view;

$simpleview->fields['progress_field']->from(50)->to(100);
$simpleview->pseudofields['created_by']->me();

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

