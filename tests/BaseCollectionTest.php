<?php

use Enzyme\Collection\Collection;

class BaseCollectionTest extends \PHPUnit_Framework_TestCase
{
    public function test_base_collection_implements_array_access_correctly()
    {
        $expected = [
            'foo' => 'bar',
        ];

        $collection = new Collection($expected);

        $this->assertEquals($expected['foo'], $collection['foo']);
        $this->assertEquals(count($expected), count($collection));

        $expected['foo'] = 'baz';
        $collection['foo'] = 'baz';
        $this->assertEquals($expected['foo'], $collection['foo']);

        $expected = [
            'foo' => 'bar',
            'baz' => 'john',
        ];
        $collection = new Collection([
            'foo' => 'bar',
            'baz' => 'john',
        ]);
        $this->assertEquals(count($expected), count($collection));

        unset($expected['foo']);
        unset($collection['foo']);
        $this->assertEquals(count($expected), count($collection));

        $this->assertEquals(isset($expected['baz']), isset($collection['baz']));

        $expected_alt = [];
        $expected_alt[] = 'foo';
        $collection_alt = new Collection([]);
        $collection_alt[] = 'foo';
        $this->assertEquals($expected_alt[0], $collection_alt[0]);
    }

    public function test_base_collection_implements_interator_correctly()
    {
        $expected = [
            'foo', 'bar', 'baz',
        ];

        $collection = new Collection($expected);

        foreach ($collection as $key => $value) {
            $this->assertEquals($expected[$key], $collection[$key]);
        }

        for ($i = 0; $i < count($expected); $i++) {
            $this->assertEquals($expected[$i], $collection[$i]);
        }
    }
}
