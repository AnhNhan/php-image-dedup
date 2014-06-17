<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

use AnhNhan\Imagery\Image;

if ($argc > 1) {
    // Do something
    sdx($argv);
    $path = sdx($argv);

    $time_start = microtime(true);
    $duplicateList = scan_and_find_duplicates($path, function ($f) {
        $img = Image::createFromFile($f);
        if (!$img) {
            throw new Exception($f);
        }
        $img->getGreyscale()->resizeTo($img->dhash_cmp_w, $img->dhash_cmp_h);
        return mt_rand(1, 10000);
    });
    $time_stop  = microtime(true);

    println('Took ' . round($time_stop - $time_start, 3) . 's.');

} else {
    // Print non-existing help
    println('No input. $1 must be a path!');
}
