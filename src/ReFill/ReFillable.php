<?php namespace ReFill;

class ReFillable
{

    public $hash;
    public $uniqueId;
    public $text;
    public $words;
    public $index;

    protected $semantic;

    public function __construct($uniqueId, $string, JsonEncoded $object)
    {
        $this->semantic = new Semantic;

        $this->uniqueId = $uniqueId;
        $this->text     = $string;
        $this->object   = $object->encoded();
        $this->hash     = md5($this->object);
        $this->words    = $this->semantic->getValidWords($string);
    }

    public function __toString()
    {
        return $this->object;
    }
}
