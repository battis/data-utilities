<?php

namespace Battis\DataUtilities;

class Path
{
    public static function canonicalize(string $path, ?string $base = null, string $separator = DIRECTORY_SEPARATOR): string
    {
        $parts = explode($separator, ($base ?? '') . $separator . $path);
        $canonical = [];
        foreach($parts as $part) {
            switch ($part) {
                case '.':
                case '':
                    // skip
                    break;
                case '..':
                    $canonical = array_slice($canonical, 0, -1);
                    break;
                default:
                    $canonical[] = $part;
            }
        }
        return (($base === null && substr($path, 0, 1) === $separator) || ($base !== null  && substr($base, 0, 1) === $separator) ? $separator : '') . join($separator, $canonical);
    }

    /**
     * Join path components together
     *
     * @see https://stackoverflow.com/a/1091219/294171 StackOverflow comment
     *
     * @param string[] $args ￼
     *
     * @return string  ￼
     */
    public static function join(...$args): string
    {
        $paths = [];
        foreach ($args as $arg) {
            $paths = array_merge($paths, (array) $arg);
        }
        $paths = array_map(fn($p) => trim($p, "/"), $paths);
        $paths = array_filter($paths);
        return join('/', $paths);
    }
}
