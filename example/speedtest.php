<?php

function simFilter($word) {
    return strlen($word) > 3;
}

$string = "Always code as if the guy who ends up " .
          "maintaining your code will be a violent psychopath " .
          "who knows where you live.";

$stopWords = [
    'I', 'a', 'about', 'an', 'are', 'as', 'at', 'be', 'by',
    'com', 'for', 'from', 'how', 'in', 'is', 'if', 'it', 'of', 'on',
    'or', 'that', 'the', 'this', 'to', 'was', 'what', 'when', 'up',
    'where', 'who', 'will', 'with', 'the', 'www'
];

$string = strtolower(trim(preg_replace('/[^a-zA-Z0-9- ]/', '', $string)));

$string = str_replace('-', ' ', $string);
$words = explode(' ', $string);

$time_start = microtime(true);

for($i = 0; $i < 100000; $i++) {
    array_filter($words, function($word) use ($stopWords) {
        return ! in_array($word, $stopWords) &&
               strlen($word) >= 3;
    });
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds<br />';

$time_start = microtime(true);


//for($i = 0; $i < 100000; $i++) {
//    $w = $words;
//    foreach($w as &$word) {
//        foreach ($stopWords as &$stop) {
//            if ($word == $stop && strlen($word) >= 3) {
//                unset($word);
//                break;
//            }
//        }
//    }
//}

for($i = 0; $i < 100000; $i++) {
    $w = $words;
    array_diff($w, $stopWords);
//    array_filter($words, 'simFilter');
    foreach($w as &$word) {
        if (strlen($word) < 3) {
            unset($word);
        }
    }
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds';
