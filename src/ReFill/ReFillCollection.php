<?php

namespace ReFill;

class ReFillCollection
{

    protected $items;

    private function __construct($list)
    {

        $this->items[] = $list;
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


}
