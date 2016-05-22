<?php

namespace Enzyme\Collection;

use ArrayAccess;
use Countable;
use Iterator;

abstract class BaseCollection implements ArrayAccess, Iterator, Countable
{
    /**
     * The list of internal items stored by this collection.
     *
     * @var array
     */
    protected $items;

    /**
     * The current iterator position.
     *
     * @var int
     */
    protected $position;

    /**
     * Instantiate a new collection with the optional provided array.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
        $this->position = 0;
    }

    /*
    | --------------------------------------------------------------------------
    | Implementation of `ArrayAccess` interface.
    | --------------------------------------------------------------------------
    */

    public function offsetExists($offset)
    {
        return true === isset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        return (true === isset($this->items[$offset]))
            ? $this->items[$offset]
            : null;
    }

    public function offsetSet($offset, $value)
    {
        if (true === is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /*
    | --------------------------------------------------------------------------
    | Implementation of `Iterator` interface.
    | --------------------------------------------------------------------------
    */

    public function current()
    {
        return $this->items[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return true === isset($this->items[$this->position]);
    }

    /*
    | --------------------------------------------------------------------------
    | Implementation of `Countable` interface.
    | --------------------------------------------------------------------------
    */

    public function count()
    {
        return count($this->items);
    }
}
