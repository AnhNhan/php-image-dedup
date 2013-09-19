<?php

include_once __DIR__ . '/../vendor/autoload.php';
include_once __DIR__ . '/../src/root.php';

$imgFolder = getSuperRoot() . 'test/images/';

$path = $_GET['path'];

@header('Content-type: image/jpg');
echo file_get_contents($imgFolder . $path);
