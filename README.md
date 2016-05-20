# Collection
[![Build Status](https://travis-ci.org/enzyme/collection.svg?branch=master)](https://travis-ci.org/enzyme/collection)
[![Coverage Status](https://coveralls.io/repos/github/enzyme/collection/badge.svg?branch=develop)](https://coveralls.io/github/enzyme/collection?branch=develop)

An all encompassing array manager.

# Installation

```bash
$ composer require enzyme/collection
```

# Usage

Creating a collection is a simple task, simply instantiate a new collection, passing it an optional array to start life of with, and you're ready to rock!

```php
use Enzyme\Collection\Collection;

$users = new Collection(['John', 'Jane', 'Harry']);

// Send each user an email.
$users->each(function ($user) {
    mail($user) // Magical mail method... you're a wizzard.
});
```

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

# Contributing

Please see `CONTRIBUTING.md`

# License

MIT - Copyright (c) 2015 Tristan Strathearn, see `LICENSE`
