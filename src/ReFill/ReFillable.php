<?php namespace ReFill;

class ReFillable
{

    public $hash;
    public $uniqueId;
    public $text;
    public $words;
    public $index;

    protected $minWordLength = 3;
    protected $semantic;

    public function __construct($uniqueId, $string, JsonEncoded $object)
    {
        $this->semantic = new Semantic;

        $this->uniqueId = $uniqueId;
        $this->text     = $string;
        $this->object   = $object->encoded();
        $this->hash     = md5($this->object);
        $this->words    = $this->semantic->getValidWords($string, $this->minWordLength);
    }

    /**
     * @return int
     */
    public function getMinWordLength()
    {
        return $this->minWordLength;
    }

    /**
     * @param int $minWordLength
     */
    public function setMinWordLength($minWordLength)
    {
        $this->minWordLength = $minWordLength;
    }

    public function __toString()
    {
        return $this->object;
    }
}
