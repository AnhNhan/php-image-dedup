<?php

use AnhNhan\Imagery\Image;

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
        echo 'F';
        return;
    }
    $hash = dhash_to_string($img->getDHash());
    unset($img);
    echo '.';
    return $hash;
}
