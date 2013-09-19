<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

use \AnhNhan\Imagery;
use \AnhNhan\Imagery\Deduplication;

$imgFolder = getSuperRoot() . 'test/images/';

$path = $_GET['path'];

$srcImg = Imagery\Image::createFromFile($imgFolder . $path);
if ($srcImg === null) {
    echo "Failed to open image {$path}!";
    exit();
}
$hashImg = Deduplication\ImageHasher::hashImage($srcImg);

@header('Content-type: image/jpg');
imagejpeg($hashImg->getImageData(), null, 100);
