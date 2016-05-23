# Collection
[![Build Status](https://travis-ci.org/enzyme/collection.svg?branch=master)](https://travis-ci.org/enzyme/collection)
[![Coverage Status](https://coveralls.io/repos/github/enzyme/collection/badge.svg?branch=develop)](https://coveralls.io/github/enzyme/collection?branch=develop)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/enzyme/collection/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/enzyme/collection/?branch=master)
[![StyleCI](https://styleci.io/repos/59178796/shield)](https://styleci.io/repos/59178796)

An all encompassing array manager.

# Installation

```bash
$ composer require enzyme/collection
```

# Usage

You can create a collection from a standard PHP array. Once you have a collection, you can make use of all the methods it exposes.

```php
use Acme\Mailer;
use Enzyme\Collection\Collection;

$users = new Collection(['John123', 'Jane456', 'Harry789']);

// Send each user an email.
$users->each(function ($user) {
    Mailer::sendWelcomeEmail($user);
});
```

The collection implements `ArrayAccess`, `Iterator` and `Countable`, so you can use it as a standard array.

```php
use Enzyme\Collection\Collection;

$users = new Collection(['John123', 'Jane456', 'Harry789']);

var_dump($users[0]); // 'John123'
```

In the example above, the equivalent and much more readable method would be `$collection->first()`.

# Available methods

| Method signature | Description |
| ---: | :--- |
| `toArray()` | Returns the current collection as a standard PHP array. |
| `has($key, $value = null)` | Checks whether the collection has the specified key, and/or key/value pair. |
| `get($key)` | Get the value associated with the specified key. If the key does not exist, a `CollectionException` will be thrown. |
| `getOrDefault($key, $default = null)` | Same as above, except instead of throwing an exception, return the provided default value. |
| `each(Closure $fn)` | Execute the callback function provided on each item in the collection. The callback function is passed `$value, $key` as arguments. If the callback returns `false`, the function will return early and will not continue iterating over the remaining items left in the collection.|
| `map(Closure $fn)` | Execute the callback function provided on each item in the collection. The callback function is passed `$value, $key` as arguments. The return value of the callback function will be saved into a new collection and that collection will be returned as a result |
| `pluck($pluck_key, $deep = true)` | Grab and return a new collection will all values that have the specified `key`. By default, this will traverse multidimensional arrays. |
| `count()` | Return the number of elements in the collection. |
| `isEmpty()` | Whether the collection is empty. |
| `first()` | Get the value of the first item in the collection. If the collection is empty, a `CollectionException` will be thrown. |
| `firstOrDefault($default = null)` | Same as above, except instead of throwing an exception, return the provided default value. |
| `last()` | Get the value of the last item in the collection. If the collection is empty, a `CollectionException` will be thrown. |
| `lastOrDefault($default = null)` | Same as above, except instead of throwing an exception, return the provided default value. |
| `only(array $keys)` | Return a new collection containing only the items in the current collection whose keys match the ones provided. |
| `except(array $keys)` | Return a new collection containing all the keys in the current collection except those whose keys match the ones provided. |
| `push($value)` | Return a new collection with the given value pushed onto a copy of the current collection's items. |
| `pushWithKey($key, $value)` | Return a new collection with the given key/value pair pushed onto a copy of the current collection's items. |
| `pushArray(array $data)` | Return a new collection with the given array pushed onto a copy of the current collection's items. |
| `filter(Closure $fn)` | Return a new collection will all items that pass the given callback functions truth test. |
| `sort(Closure $fn)` | Return a new collection after the user defined sort function has been executed on the items. Same callback function parameters as the PHP `usort` function. |
| `hasCount($min, $max = null)` | Checks whether the collection has the minimum count specified, or a count that falls within the range specified. |
| `keys()` | Returns an array of all the keys used by the collection. |

# Contributing

Please see `CONTRIBUTING.md`

# License

MIT - Copyright (c) 2016 Tristan Strathearn, see `LICENSE`
