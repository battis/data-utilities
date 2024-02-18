<?php

namespace Battis\DataUtilities;

class Path
{
    public static function canonicalize(string $path, ?string $base = null, string $separator = DIRECTORY_SEPARATOR): string
    {
        if (substr($path, 0, strlen($separator)) !== $separator & $base !== null) {
            $path = $base . $separator . $path;
        }
        $parts = explode($separator, $path);
        $canonicalParts = [];
        foreach($parts as $part) {
            switch ($part) {
                case '.':
                case '':
                    // skip
                    break;
                case '..':
                    $canonicalParts = array_slice($canonicalParts, 0, -1);
                    break;
                default:
                    $canonicalParts[] = $part;
            }
        }

        return(substr($path, 0, strlen($separator)) === $separator ? $separator : '') . join($separator, $canonicalParts);
    }

    /**
     * Join path components together
     *
     * @param array{0: string, 1: array<string|string[]>}|array<string|string[]> $args
     *   ```php
     *   Path::join('a/', '/b', 'c/'); // "a/b/c/" -- de-dup separators
     *   Path::join(['a/','/b'], '/c/'): // "a/b/c/" -- string or string[] parts
     *   Path::join("\\", ['a', 'b', 'c']); // "a\\b\\c" -- separator as first argument, followed by array
     *   Path::join("@@@", ['a', 'b', 'c']); // "a@@@b@@@c" -- arbitrary length separator
     *   Path::join('a', null, 'b', [], 'c'); // "'"a/b/c" -- nulls and empty arrays ignored
     *   ```
     *
     * @return string
     */
    public static function join(...$args): string
    {
        $separator = DIRECTORY_SEPARATOR;
        if (count($args) === 2 && is_string($args[0]) && is_array($args[1])) {
            $separator = $args[0];
            $args = $args[1];
        }
        $paths = [];
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array) $arg);
        }
        $paths = array_values(array_filter($paths, fn($p) => !empty($p)));
        if (count($paths) > 0) {
            $start = $paths[0];
            $end = $paths[count($paths) - 1];
            $paths = array_map(fn($part) => trim($part, $separator), $paths);
            if (substr($start, 0, strlen($separator)) === $separator) {
                $paths[0] = $separator . $paths[0];
            }
            if(substr($end, -strlen($separator)) === $separator) {
                $paths[count($paths) - 1] = $paths[count($paths) - 1] . $separator;
            }
            return join($separator, $paths);
        }
        return "";
    }
}
