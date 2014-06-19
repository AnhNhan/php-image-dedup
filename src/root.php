<?php

include_once __DIR__ . '/helpers.php';
include_once __DIR__ . '/scan.php';

function scan_and_find_duplicates($path, callable $function)
{
    $fileList = scan_dir_for_images($path);
    YamwLibs\Libs\Cli\Cli::notice(sprintf('Scanning %s ... (%d files)', $path, count($fileList)));

    $hashList = hash_each_file($fileList, $function);

    $duplicateList = igroup($hashList, 'hash');

    return $duplicateList;
}

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

/**
 * Generates the path pointing to the specified resource
 *
 * @param string $val
 * The path from the application root to the resource
 *
 * @param bool $base
 * If true, it will generate an absolute path, otherwise it will generate a
 * relative one
 *
 * @return string
 * <p>The path to the resource</p>
 */
function path($val = '', $base = true)
{
    if ($base) {
        return str_replace(
            array('//', '/', '\\', '\\\\'),
            array(
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR
            ),
            getRoot().DIRECTORY_SEPARATOR.$val
        );
    } else {
        return str_replace(
            array('//', '/', '\\', '\\\\'),
            array(
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
                DIRECTORY_SEPARATOR,
            ),
            $val
        );
    }
}

/**
 * Whether the current process is a CLI interface or not
 *
 * @return boolean
 * True if PHP is running in CLI mode. Else False.
 */
function isCli()
{
    return php_sapi_name() == 'cli';
}

function isWindows()
{
    // This is the easiest way to check for Windows - and the fastest :D
    return DIRECTORY_SEPARATOR === '\\';
}

function println($line = '')
{
    echo $line . PHP_EOL;
}

/**
 * Similar to `idx()`, but with `array_shift()`
 *
 * @param array $array
 * @param mixed $default
 *
 * @return mixed The shifted element of the array, or `$default`
 */
function sdx(array &$array, $default = null)
{
    $return = array_shift($array);
    return $return ?: $default;
}

/**
 * Similar to `pdx()`, but with `array_pop()`
 *
 * @param array $array
 * @param mixed $default
 *
 * @return mixed The element that got popped off the end, or `$default`
 */
function pdx(array &$array, $default = null)
{
    $return = array_pop($array);
    return $return ?: $default;
}

function scanInDirectory($dirName, $extension = '.*', $recursive = false)
{
    $dirList = scandir($dirName);
    $files = array();

    foreach ($dirList as $entry) {
        if (in_array($entry, array('.', '..'))) {
            continue;
        }

        $name = $dirName . '/' . $entry;
        if (is_dir($name) && $recursive) {
            $files = array_merge($files, scanInDirectory($name, $extension, true));
        } else {
            if (
                !strpos($entry, '.') !== 0 &&
                preg_match('/'.$extension.'$/', $entry)
            ) {
                $files[] = $name;
            }
        }
    }

    return $files;
}

function sanitizeStringsFromPrefix(array $paths, $prefix)
{
    $files = array();

    // Sanitize file names to be relative instead of absolute
    foreach ($paths as $file) {
        $newFileName = str_replace(
            "\\",
            "/",
            ltrim(
                preg_replace('/^'.preg_quote($prefix, '/').'/', "", $file),
                " \\/"
            )
        );

        $files[] = $newFileName;
    }

    return $files;
}
