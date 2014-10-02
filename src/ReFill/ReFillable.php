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
        return array_filter(preg_split('/[-\s]/', $string));
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
        return array_filter($words, function($word) {
            return ! in_array($word, $this->stopWords) &&
                   strlen($word) >= $this->minWordLength;
        });
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
