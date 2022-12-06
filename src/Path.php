<?php

namespace Battis\DataUtilities;

class Path
{
    public static function canonicalize(string $path, ?string $base = null, string $separator = DIRECTORY_SEPARATOR): string
    {
        $parts = explode($separator, ($base ?? '') . $separator . $path);
        $canonical = [];
        foreach($parts as $part) {
            switch ($part)
            {
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
}
