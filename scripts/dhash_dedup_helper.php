<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

use YamwLibs\Libs\Cli\Color;
use YamwLibs\Libs\Cli\Cli;

use AnhNhan\Imagery\Image;

if ($argc > 1) {
    // Do something
    sdx($argv);
    $path = sdx($argv);

    function dhash_to_string(array $dhash)
    {
        return implode('', array_map(function ($n) {
            return $n == 0 ? '0' : '1';
        }, $dhash));
    }

    function generate_dhash_from_file($path)
    {
        $img = Image::createFromFile($path);
        if (!$img)
        {
            return;
        }
        $hash = dhash_to_string($img->getDHash());
        return $hash;
    }

    $time_start = microtime(true);

    $duplicateList = scan_and_find_duplicates($path, 'generate_dhash_from_file');

    $count = 0;
    foreach ($duplicateList as $hash => $arr) {
        if (count($arr) == 1) {
            // Not a duplicate
            unset($duplicateList[$hash]);
        } else {
            $count += count($arr);
        }
    }

    Cli::success('We have ' . Color::green(count($duplicateList)) .
        ' duplicate groups, with ' . Color::yellow($count) . ' duplicates');

    foreach ($duplicateList as $duplicate) {
        println(str_repeat('-', 79));

        foreach ($duplicate as $entry) {
            println($entry['name']);
        }

        println(str_repeat('-', 79));
        println();
    }

    $time_stop  = microtime(true);
    println('Took ' . round($time_stop - $time_start, 3) . 's.');
} else {
    // Print non-existing help
    Cli::notice('No input. $1 must be a path!');
}
