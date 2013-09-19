<?php

/**
 * Gets the absolute root path to where this application resides
 *
 * @return string
 * The absolute path to the application root
 */
function getRoot()
{
    static $root;
    if (!$root) {
        $root = __DIR__ . DIRECTORY_SEPARATOR;
    }
    return $root;
}

function getSuperRoot()
{
    static $superRoot;
    if (!$superRoot) {
        $superRoot = dirname(getRoot()) . DIRECTORY_SEPARATOR;
    }
    return $superRoot;
}
