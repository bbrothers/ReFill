<?php namespace ReFill;


class Semantic
{
    protected $stopWords = [
        'I', 'a', 'about', 'an', 'are', 'as', 'at', 'be', 'by',
        'com', 'for', 'from', 'how', 'in', 'is', 'if', 'it', 'of', 'on',
        'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'up',
        'where', 'who', 'will', 'with', 'the', 'www'
    ];

    public function sanitize($string)
    {
        return strtolower(trim(preg_replace('/[^a-zA-Z0-9- ]/', '', $string)));
    }

    public function buildIndex($word, $minWordLength)
    {
        $letters = [];
        for ($i = $minWordLength, $len = strlen($word); $i <= $len; $i++) {
            $letters[$i] = substr($word, 0, $i);
        }
        return $letters;
    }

    public function splitWords($string)
    {
        $string = str_replace('-', ' ', $string);
        return explode(' ', $string);
    }

    public function discardInvalidWords($words, $minWordLength)
    {
        $words = array_diff($words, $this->stopWords);
        foreach($words as $index => $word) {
            if (strlen($word) < $minWordLength) {
                unset($words[$index]);
            }
        }
        return $words;
    }

    public function getValidWords($string, $minWordLength)
    {
        return $this->discardInvalidWords(
            $this->splitWords($string),
            $minWordLength
        );
    }
}
