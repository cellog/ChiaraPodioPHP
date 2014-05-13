ChiaraPodioPHP
==============

Advanced PHP library for Podio based on the existing Podio API

#Search

Searching for information in Podio is accessible through the `/search/` API,
and the ChiaraPodioPHP library makes this very easy to access.

```php
<?php
$limit = 20; // default value, limit to 20 items
$offset = 0; // default value, retrieve results from the start.
$results = Chiara\Remote::search('test', $limit, $offset);

// determine whether a particular item is present in search results
// by checking if its id is present
if (isset($results->item[1543256])) {
    // do something...
}

// check for the presence of a particular status
if (isset($results->status[154534325])) {
    // do something...
}

// check for the presence of a particular task
if (isset($results->task[548925432])) {
    // do something...
}

// iterate over all results
foreach ($results as $info) {
    if ($info instanceof Chiara\PodioItem) {
        echo $info->title;
    } else if ($info instanceof Chiara\PodioTask) {
        echo $info->text;
    } else if ($info instanceof Chiara\PodioStatus) {
        echo $info->value;
    }
}

// iterate over only the status results
foreach ($results->status as $status) {
    echo $status->value;
}

if (count($results->item)) {
    echo "Items found matching the search term";
}

// if after using/looking at these search items, you want to retrieve more
// retrieve starting from the 21st search result
$nextresults = Chiara\Remote::search('test', $limit, $limit + 1);
?>
```

##Global

Searching for something in all of the organizations and workspaces you have access
to is quite simple:

```php
<?php
$results = Chiara\Remote::search('search string');
?>
```
Note that global search cannot be done using app authentication, and the
API will throw an exception before contacting the server if you try.

##Organization

There are two ways to search for things in a specific organization:

```php
<?php
$org = new Chiara\PodioOrganization(123);

// first
$results = Chiara\Remote::search($org, 'search string');
// second
$results = $org->search('search string');
?>
```

Note that organization-specific search cannot be done using app authentication, and the
API will throw an exception before contacting the server if you try.

##Workspace

There are two ways to search for things in a specific workspace:

```php
<?php
$space = new Chiara\PodioWorkspace(123);

// first
$results = Chiara\Remote::search($space, 'search string');
// second
$results = $space->search('search string');
?>
```

Note that workspace-specific search cannot be done using app authentication, and the
API will throw an exception before contacting the server if you try.

##App

There are two ways to search for things in a specific application:

```php
<?php
$app = new Chiara\PodioApp(123);

// first
$results = Chiara\Remote::search($app, 'search string');
// second
$results = $app->search('search string');
?>
```

Note that app-specific search can be done using app authentication.