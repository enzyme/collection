<?php

namespace Enzyme\Collection;

use Closure;

class Collection
{
    protected $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function has($key, $value = null)
    {
        $key_exists = self::keyExists($key, $this->items);

        return null !== $value
            ? $value === $this->get($key)
            : $key_exists;
    }

    public function get($key, $default = null)
    {
        if (false === $this->hasKey($key)) {
            return $default;
        }

        return $this->items[$key];
    }

    public function each(Closure $fn)
    {
        foreach ($this->items as $key => $value) {
            if (false === $fn($value, $key)) {
                break;
            }
        }
    }

    public function map(Closure $fn)
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            $results[] = $fn($value, $key);
        }

        return new static($results);
    }

    public function pluck($pluck_key, $deep = false)
    {
        return self::pluckKey($this->items, $pluck_key, $deep);
    }

    public function count()
    {
        return count($this->items);
    }

    public function isEmpty()
    {
        return $this->count() < 1;
    }

    public function first()
    {
        if (true === $this->isEmpty()) {
            throw new CollectionException(
                'Cannot get first item as the collection is empty.'
            );
        }

        return reset($this->items);
    }

    public function firstOrDefault($default = null)
    {
        try {
            return $this->first();
        } catch (CollectionException $e) {
            return $default;
        }
    }

    public function last()
    {
        //
    }

    public function only(array $keys)
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return (true === self::keyExists($key, $keys));
        });
    }

    public function except(array $keys)
    {
        return $this->filter(function ($value, $key) use ($keys) {
            return (false === self::keyExists($key, $keys));
        });
    }

    public function pop()
    {
        //
    }

    public function push($value, $key = null)
    {
        $items = $this->items;

        if (null === $key) {
            $items[] = $value;

            return new static($items);
        }

        $items[$key] = $value;

        return new static($items);
    }

    public function filter(Closure $fn)
    {
        $results = [];
        foreach ($this->items as $key => $value) {
            if (true === $fn($value, $key)) {
                $results[$key] = $value;
            }
        }

        return new static($results);
    }

    protected static function keyExists($key, $collection)
    {
        return true === isset($collection[$key]);
    }

    protected static function pluckKey($collection, $pluck_key, $deep)
    {
        $results = [];
        foreach ($collection as $key => $value) {
            if (true === $deep && true === is_array($value)) {
                $deeper_results = self::pluckKey($value, $pluck_key, $deep);

                foreach ($deeper_results as $key => $value) {
                    $results[$key] = $value;
                }

                continue;
            }

            if ($key === $pluck_key) {
                $results[$key] = $value;
            }
        }

        return new static($results);
    }
}
