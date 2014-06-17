<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

if ($argc > 1) {
    // Do something
    sdx($argv);
    $path = sdx($argv);

    $time_start = microtime(true);
    $duplicateList = scan_and_find_duplicates($path, function ($f) { return @hash_file('crc32', $f); });
    $time_stop  = microtime(true);

    println('Took ' . round($time_stop - $time_start, 3) . 's.');

} else {
    // Print non-existing help
    println('No input. $1 must be a path!');
}
