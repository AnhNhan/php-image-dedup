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
            throw new Exception("Could not process file '$path'!");
        }
        $hash = dhash_to_string($img->getDHash());
        return $hash;
    }

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

    /*
    foreach ($duplicateList as $duplicate) {
        println(str_repeat('-', 79));

        foreach ($duplicate as $entry) {
            println($entry['name']);
        }

        println(str_repeat('-', 79));
    }*/

    foreach ($duplicateList as $duplicates) {
        $first = sdx($duplicates);

        $i = 1;
        println('  ' . $first['name']);
        foreach ($duplicates as $entry) {
            $oldName = $entry['name'];
            $newName = preg_replace('/\.(jpg|png)$/', '_' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.$1', $first['name']);
            //Cli::notice(sprintf('Renaming %s to %s', Color::yellow($oldName), Color::green($newName)));
            //rename($oldName, $newName);
            Cli::notice(sprintf('File %s is a duplicate of %s', Color::yellow($oldName), Color::green($first['name'])));
            ++$i;
        }

        println();
        println();
    }
} else {
    // Print non-existing help
    Cli::notice('No input. $1 must be a path!');
}
