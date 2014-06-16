<?php

function scan_dir_for_images($path)
{
    return scanInDirectory($path, '\.(jpg|png)', true);
}

function hash_each_file(array $files, callable $fun)
{
    $hashList = [];

    foreach ($files as $file) {
        $hash = $fun($file);
        if (!$hash) {
            YamwLibs\Libs\Cli\Cli::error("Could not process file '$file'!");
            continue;
        }
        $hashList[] = [
            'name' => $file,
            'hash' => $hash,
        ];
    }

    return $hashList;
}
