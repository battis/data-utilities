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
     * @param string[]|array $args ￼
     *
     * @return string  ￼
     */
    public static function join(...$args): string
    {
        $separator = DIRECTORY_SEPARATOR;
        $paths = [];
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array) $arg);
        }
        $start = $paths[0];
        $end = $paths [count($paths) - 1];
        $paths = array_map(fn($part) => trim($part, $separator), $paths);
        if (substr($start, 0, strlen($separator)) === $separator) {
            $paths[0] = $separator . $paths[0];
        }
        if(substr($end, -strlen($separator)) === $separator) {
            $paths[count($paths) - 1] = $paths[count($paths) - 1] . $separator;
        }
        return join($separator, $paths);
    }
}
