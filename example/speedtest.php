<?php

$string = "Always code as if the guy who ends up " .
          "maintaining your code will be a violent psychopath " .
          "who knows where you live.";

$time_start = microtime(true);


for($i = 0; $i < 1000000; $i++) {
    array_filter(preg_split('/[-\s]/', $string));
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds';


$time_start = microtime(true);


for($i = 0; $i < 1000000; $i++) {
    $string = str_replace('-', ' ', $string);
    explode(' ', $string);
}

$time_end = microtime(true);

$execution_time = ($time_end - $time_start);

echo '<b>Total Execution Time:</b> '.$execution_time.' Seconds';
