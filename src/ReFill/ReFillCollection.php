<?php

namespace ReFill;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class ReFillCollection implements ArrayAccess, Countable, IteratorAggregate
{

    protected $items;

    private function __construct($items)
    {

        $this->items = $items;
    }

    public static function fromArray(array $items, $uniqueId = 'id', $text = 'name')
    {

        foreach ($items as &$item) {
            $item = new ReFillable($item[$uniqueId], $item[$text]);
        }

        return new self($items);

    }

    public static function fromKeyValue(array $items)
    {

        foreach ($items as $key => &$item) {
            $item = new ReFillable($key, $item);
        }

        return new self($items);

    }

    public static function fromObject(array $items, $uniqueId = 'id', $text = 'name')
    {

        foreach ($items as &$item) {
            $item = new ReFillable($item->{$uniqueId}, $item->{$text});
        }

        return new self($items);

    }

    public function count()
    {
        return count($this->items);
    }

    public function offsetExists($key)
    {
        return array_key_exists($key, $this->items);
    }

    public function offsetGet($key)
    {
        return $this->items[$key];
    }

    public function offsetSet($key, $value)
    {
        if (is_null($key)) {
            $this->items[] = $value;
        } else {
            $this->items[$key] = $value;
        }
    }

    public function offsetUnset($key)
    {
        unset($this->items[$key]);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
