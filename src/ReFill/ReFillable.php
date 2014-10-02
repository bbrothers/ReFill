<?php

namespace ReFill;

use JsonSerializable;

class ReFillable implements JsonSerializable
{

    protected $stopWords = [
        'I', 'a', 'about', 'an', 'are', 'as', 'at', 'be', 'by',
        'com', 'for', 'from', 'how', 'in', 'is', 'if', 'it', 'of', 'on',
        'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'up',
        'where', 'who', 'will', 'with', 'the', 'www'
    ];

    public $hash;
    public $uniqueId;
    public $text;
    public $words;
    public $index;

    protected $minWordLength = 3;

    public function __construct($uniqueId, $string)
    {
        $this->uniqueId = $uniqueId;
        $this->text     = $string;
        $this->hash     = md5(json_encode($this));
        $this->words    = $this->discardInvalidWords(
            $this->splitWords($this->clean($string))
        );
    }

    public function clean($string)
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9- ]/', '', $string)));
    }

    public function splitWords($string)
    {
        $string = str_replace('-', ' ', $string);
        return explode(' ', $string);
    }

    public function buildIndex($word)
    {
        $letters = [];
        for ($i = $this->minWordLength, $len = strlen($word); $i <= $len; $i++) {
            $letters[$i] = substr($word, 0, $i);
        }
        return $letters;
    }

    public function discardInvalidWords($words)
    {
        $words = array_diff($words, $this->stopWords);
        foreach($words as $index => $word) {
            if (strlen($word) < $this->minWordLength) {
                unset($words[$index]);
            }
        }
        return $words;
    }

    public function jsonSerialize()
    {
        return [
            'id'       => $this->uniqueId,
            'text'     => $this->text,
        ];
    }

    public function __toString()
    {
        return json_encode($this);
    }
}
