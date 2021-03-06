<?php

namespace Enzyme\Collection;

use Closure;

class Collection extends BaseCollection
{
    /**
     * Static helper method to instantiate a new collection. Useful for when you
     * want to immediately chain a method. Eg: Collection::make([1, 2, 3])->map(...).
     *
     * @param array $initial
     *
     * @return Collection
     */
    public static function make(array $initial)
    {
        return new static($initial);
    }

    /**
     * Get a PHP style array from the current collection.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->items;
    }

    /**
     * Whether the collection has the specified key, and/or value associated
     * with the specified key.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return bool
     */
    public function has($key, $value = null)
    {
        $key_exists = self::keyExists($key, $this->items);

        return null !== $value
            ? $value === $this->get($key)
            : $key_exists;
    }

    /**
     * Get the value associated with the specified key.
     *
     * @param string $key
     *
     * @throws \Enzyme\Collection\CollectionException If the key does not exist.
     *
     * @return mixed
     */
    public function get($key)
    {
        if (false === self::keyExists($key, $this->items)) {
            throw new CollectionException(
                "An element with the key [${key}] does not exist."
            );
        }

        return $this->items[$key];
    }

    /**
     * Get the value associated with the specified key or return a default value
     * instead if it does not exist.
     *
     * @param string $key
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getOrDefault($key, $default = null)
    {
        try {
            return $this->get($key);
        } catch (CollectionException $e) {
            return $default;
        }
    }

    /**
     * Execute the given callback function for each element in this collection.
     *
     * @param Closure $fn
     */
    public function each(Closure $fn)
    {
        foreach ($this->items as $key => $value) {
            if (false === $fn($value, $key)) {
                break;
            }
        }
    }

    /**
     * Execute the given callback function for each element in this collection
     * and save the results to a new collection.
     *
     * @param Closure $fn
     *
     * @return \Enzyme\Collection\Collection
     */
    public function map(Closure $fn)
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            $results[] = $fn($value, $key);
        }

        return new static($results);
    }

    /**
     * Execute the given callback function for each element in this collection
     * and save the results to a new collection with the specified key. The
     * callback function should return a 1 element associative array, eg:
     * ['key' => 'value'] to be mapped.
     *
     * @param Closure $fn
     *
     * @return \Enzyme\Collection\Collection
     */
    public function mapWithKey(Closure $fn)
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            $result = $fn($value, $key);
            $keys = array_keys($result);

            if (count($keys) < 1) {
                throw new CollectionException(
                    'Map with key expects a 1 element associative array.'
                );
            }

            $results[$keys[0]] = $result[$keys[0]];
        }

        return new static($results);
    }

    /**
     * Pluck out all values in this collection which have the specified key.
     *
     * @param string $pluck_key
     * @param bool   $deep      Whether to traverse into sub-arrays.
     *
     * @return \Enzyme\Collection\Collection
     */
    public function pluck($pluck_key, $deep = true)
    {
        return self::pluckKey($this->items, $pluck_key, $deep);
    }

    /**
     * Get the number of elements in this collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Whether this collection is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count() < 1;
    }

    /**
     * Get the value of the first element in this collection.
     *
     * @throws \Enzyme\Collection\CollectionException If the collection is empty.
     *
     * @return mixed
     */
    public function first()
    {
        if (true === $this->isEmpty()) {
            throw new CollectionException(
                'Cannot get first item as the collection is empty.'
            );
        }

        return reset($this->items);
    }

    /**
     * Get the value of the first element in this collection or return the
     * default value specified if the collection is empty.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function firstOrDefault($default = null)
    {
        try {
            return $this->first();
        } catch (CollectionException $e) {
            return $default;
        }
    }

    /**
     * Get the value of the last element in this collection.
     *
     * @throws \Enzyme\Collection\CollectionException If the collection is empty.
     *
     * @return mixed
     */
    public function last()
    {
        if (true === $this->isEmpty()) {
            throw new CollectionException(
                'Cannot get last element as collection is empty.'
            );
        }

        end($this->items);
        $key = key($this->items);
        reset($this->items);

        return $this->items[$key];
    }

    /**
     * Get the value of the last element in this collection or return the
     * default value specified if the collection is empty.
     *
     * @param mixed $default
     *
     * @return mixed
     */
    public function lastOrDefault($default = null)
    {
        try {
            return $this->first();
        } catch (CollectionException $e) {
            return $default;
        }
    }

    /**
     * Get a new collection of only the elements in the current collection
     * that have the specified keys.
     *
     * @param array $keys
     *
     * @return \Enzyme\Collection\Collection
     */
    public function only(array $keys)
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return true === self::keyExists($key, array_flip($keys));
        });
    }

    /**
     * Get a new collection of all the elements in the current collection
     * except those that have the specified keys.
     *
     * @param array $keys
     *
     * @return \Enzyme\Collection\Collection
     */
    public function except(array $keys)
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return false === self::keyExists($key, array_flip($keys));
        });
    }

    /**
     * Return a new collection with the current collection's elements plus the
     * given value pushed onto the end of the array.
     *
     * @param mixed $value
     *
     * @return \Enzyme\Collection\Collection
     */
    public function push($value)
    {
        $items = $this->items;
        $items[] = $value;

        return new static($items);
    }

    /**
     * Return a new collection with the current collection's elements plus the
     * given key and value pushed onto the end of the array.
     *
     * @param string $key
     * @param mixed  $value
     *
     * @return \Enzyme\Collection\Collection
     */
    public function pushWithKey($key, $value)
    {
        $items = $this->items;
        $items[$key] = $value;

        return new static($items);
    }

    /**
     * Return a new collection with the current collection's elements plus the
     * given array pushed onto the end of the array.
     *
     * @param array $data
     *
     * @return \Enzyme\Collection\Collection
     */
    public function pushArray(array $data)
    {
        $items = $this->items;
        foreach ($data as $key => $value) {
            if (true === is_int($key)) {
                $items[] = $value;
            } else {
                $items[$key] = $value;
            }
        }

        return new static($items);
    }

    /**
     * Return a new collection with a subset of all the current collection's
     * elements that pass the given callback functions truth test.
     *
     * @param Closure $fn
     *
     * @return \Enzyme\Collection\Collection
     */
    public function filter(Closure $fn)
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            if (true === $fn($value, $key)) {
                $results[$key] = $value;
            }
        }

        // Pushing this new array will normalize numeric keys if they exist.
        // After filtering, they may not start at zero and sequentially
        // go upwards, which is generally not expected.
        return (new static())->pushArray($results);
    }

    /**
     * Sort the collection using the provided callback function. Same expected
     * parameters and the PHP usort function.
     *
     * @param Closure $fn
     *
     * @return \Enzyme\Collection\Collection
     */
    public function sort(Closure $fn)
    {
        $sorted = $this->items;
        $result = usort($sorted, $fn);

        if (false === $result) {
            throw new CollectionException(
                'The collection could be not sorted.'
            );
        }

        return new static($sorted);
    }

    /**
     * Whether this collection has the specified number of elements within the
     * given range or equal too or above the minimum value specified.
     *
     * @param int $min
     * @param int $max Default is null.
     *
     * @return bool
     */
    public function hasCount($min, $max = null)
    {
        if (null === $max) {
            return $this->count() >= $min;
        }

        return $this->count() >= $min
            && $this->count() <= $max;
    }

    /**
     * Get a list of the keys used by this collection.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->items);
    }

    /**
     * Checks whether the specified key exists in the given collection.
     *
     * @param string $key
     * @param array  $collection
     *
     * @return bool
     */
    protected static function keyExists($key, array $collection)
    {
        return true === isset($collection[$key]);
    }

    /**
     * Pluck all the values that have the specified key from the given
     * collection.
     *
     * @param array  $collection
     * @param string $pluck_key
     * @param bool   $deep       Whether to traverse into sub-arrays.
     *
     * @return \Enzyme\Collection\Collection
     */
    protected static function pluckKey(array $collection, $pluck_key, $deep)
    {
        $results = [];
        foreach ($collection as $key => $value) {
            if (true === $deep && true === is_array($value)) {
                $deeper_results = self::pluckKey(
                    $value,
                    $pluck_key,
                    $deep
                )->toArray();

                foreach ($deeper_results as $deep_value) {
                    $results[] = $deep_value;
                }

                continue;
            }

            if ($key === $pluck_key) {
                $results[] = $value;
            }
        }

        return new static($results);
    }
}
