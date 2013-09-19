<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

use \YamwLibs\Libs\Cli;

if ($argc > 1) {
    // Do something
    sdx($argv);
    $path = sdx($argv);


    $fileList = scanInDirectory($path, '\.(jpg|png)', false);
    Cli\Cli::notice(sprintf('Scanning %s ... (%d files)', $path, count($fileList)));
    $hashList = [];

    foreach ($fileList as $file) {
        $hashList[] = [
            'name' => $file,
            'hash' => sha1_file($file) . md5_file($file) . crc32(file_get_contents($file)),
        ];
    }

    $duplicateList = igroup($hashList, 'hash');
    $count = 0;
    foreach ($duplicateList as $hash => $arr) {
        if (count($arr) == 1) {
            // Not a duplicate
            unset($duplicateList[$hash]);
        } else {
            $count += count($arr);
        }
    }

    Cli\Cli::success('We have ' . Cli\Color::green(count($duplicateList)) .
        ' duplicate groups, with ' . Cli\Color::yellow($count) . ' duplicates');

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
        foreach ($duplicates as $entry) {
            $oldName = $entry['name'];
            $newName = preg_replace('/\.(.*?)/', '_' . str_pad($i, 3, '0', STR_PAD_LEFT) . '.$1', $first['name']);
            Cli\Cli::notice(sprintf('Renaming %s to %s', Cli\Color::yellow($oldName), Cli\Color::green($newName)));
            rename($oldName, $newName);
        }

        println();
    }
} else {
    // Print non-existing help
    Cli\Cli::notice('No input. $1 must be a path!');
}
