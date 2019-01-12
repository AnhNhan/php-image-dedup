<?php

use AnhNhan\Imagery\Image;

function dhash_to_string(array $dhash)
{
    return implode('', array_map(function ($n) {
        return $n == 0 ? '0' : '1';
    }, $dhash));
}

function dhash_to_int(array $dhash)
{
    $ii = 0;
    $input_size = count($dhash);
    return array_reduce($dhash, function ($result, $next) use (&$ii, $input_size) {
        return $result |= $next << ($input_size - ++$ii);
    }, 0);
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
