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

    $img = Image::createFromFile($path);
    $hash = dhash_to_string($img->getDHash());
    println($hash);
} else {
    // Print non-existing help
    Cli::notice('No input. $1 must be a path!');
}
