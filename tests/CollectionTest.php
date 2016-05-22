<?php

use Enzyme\Collection\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_collection_stores_values_as_expected()
    {
        $expected = [
            'foo' => 'bar',
        ];

        $collection = new Collection($expected);

        $this->assertEquals(true, $collection->has('foo'));

        // Check key/value pair existence.
        $this->assertEquals(true, $collection->has('foo', 'bar'));
        $this->assertEquals(false, $collection->has('foo', 'baz'));

        $this->assertEquals($expected['foo'], $collection->get('foo'));
        $this->assertEquals($expected['foo'], $collection->first());
        $this->assertEquals($expected, $collection->toArray());
        $this->assertEquals(count($expected), $collection->count());
        $this->assertEquals(false, $collection->isEmpty());
    }

    public function test_collection_gets_last_values_as_expected()
    {
        $original_array = [
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $collection = new Collection($original_array);

        $this->assertEquals($original_array['bar'], $collection->last());
    }

    public function test_collection_gets_default_values_as_expected()
    {
        $collection = new Collection();

        $this->assertEquals('bar', $collection->getOrDefault('foo', 'bar'));
        $this->assertEquals(null, $collection->getOrDefault('foo'));
        $this->assertEquals('bar', $collection->firstOrDefault('bar'));
        $this->assertEquals(null, $collection->firstOrDefault());
        $this->assertEquals('bar', $collection->lastOrDefault('bar'));
        $this->assertEquals(null, $collection->lastOrDefault());
    }

    public function test_collection_method_each_works_as_expected()
    {
        $original_array = [
            'foo' => 'bar',
            'baz' => 'john',
        ];

        $collection = new Collection($original_array);
        $collection->each(function ($value, $key) use ($original_array) {
            $this->assertEquals($original_array[$key], $value);
        });
    }

    public function test_collection_method_map_works_as_expected()
    {
        $original_array = [
            'foo' => 'bar',
            'baz' => 'john',
        ];

        $expected = [
            'foo.bar',
            'baz.john',
        ];

        $collection = new Collection($original_array);
        $actual = $collection->map(function ($value, $key) {
            return $key.'.'.$value;
        });

        $this->assertEquals($expected, $actual->toArray());
    }

    public function test_collection_method_pluck_works_as_expected()
    {
        $original_array = [
            [
                'foo' => 'bar1',
                'bar' => 'baz1',
            ],
            [
                'foo' => 'bar2',
                'bar' => 'baz2',
            ],
        ];

        $expected = [
            'bar1',
            'bar2',
        ];

        $collection = new Collection($original_array);
        $actual = $collection->pluck('foo');

        $this->assertEquals($expected, $actual->toArray());
    }

    public function test_collection_push_methods_work_as_expected()
    {
        // Simple key-less push.
        $original_array = ['foo' => 'bar'];
        $expected = ['foo' => 'bar', 'baz'];
        $collection = new Collection($original_array);
        $collection = $collection->push('baz')->toArray();
        $this->assertEquals($expected, $collection);

        // Key & value push.
        $original_array = ['foo' => 'bar'];
        $expected = ['foo' => 'bar', 'baz' => 'john'];
        $collection = new Collection($original_array);
        $collection = $collection->pushWithKey('baz', 'john')->toArray();
        $this->assertEquals($expected, $collection);

        // Array push type 1.
        $original_array = ['foo' => 'bar'];
        $expected = ['foo' => 'bar', 'baz' => 'john'];
        $collection = new Collection($original_array);
        $collection = $collection->pushArray(['baz' => 'john'])->toArray();
        $this->assertEquals($expected, $collection);

        // Array push type 2.
        $original_array = ['foo' => 'bar'];
        $expected = ['foo' => 'bar', 'baz', 'john'];
        $collection = new Collection($original_array);
        $collection = $collection->pushArray(['baz', 'john'])->toArray();
        $this->assertEquals($expected, $collection);
    }

    public function test_collection_only_except_and_filter_methods_work_as_expected()
    {
        // The 'filter' method is being tested indirectly here by the use
        // of 'only' and 'accept' which make use of it internally.

        // Testing 'only'.
        $original_array = ['foo' => 'bar', 'bar' => 'baz', 'john' => 'doe'];
        $expected = ['foo' => 'bar', 'john' => 'doe'];
        $collection = new Collection($original_array);
        $collection = $collection->only(['foo', 'john'])->toArray();
        $this->assertEquals($expected, $collection);

        // Testing 'except'.
        $original_array = ['foo' => 'bar', 'bar' => 'baz', 'john' => 'doe'];
        $expected = ['foo' => 'bar', 'john' => 'doe'];
        $collection = new Collection($original_array);
        $collection = $collection->except(['bar'])->toArray();
        $this->assertEquals($expected, $collection);
    }

    /**
     * @expectedException \Enzyme\Collection\CollectionException
     */
    public function test_collection_throws_exception_for_non_existent_element_1()
    {
        $collection = new Collection();
        $collection->get('foo');
    }

    /**
     * @expectedException \Enzyme\Collection\CollectionException
     */
    public function test_collection_throws_exception_for_non_existent_element_2()
    {
        $collection = new Collection();
        $collection->first();
    }

    /**
     * @expectedException \Enzyme\Collection\CollectionException
     */
    public function test_collection_throws_exception_for_non_existent_element_3()
    {
        $collection = new Collection();
        $collection->last();
    }
}
