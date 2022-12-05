<?php

namespace Battis\DataUtilities;

class URL
{
    /**
     * Generate a URL from a path
     *
     * @param string $path A valid file path
     * @param string|false $basePath The base path from which to start when
     *                               processing a relative path.
     * @return false|string The URL to that path, or `false` if the URL cannot
     *     be computed (e.g. if run from CLI)
     * @deprecated 1.1.2 No longer tested, and appears to be oriented towards
     *     a particular version of Apache running on Ubuntu
     */
    public static function fromPath($path, $basePath = false)
    {
        global $_SERVER;

        if ($basePath !== false && substr($path, 0, 1) !== '/') {
            $basePath = rtrim($basePath, '/');
            $path = realpath("$basePath/$path");
        }

        if (realpath($path)) {
            return (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] != 'on' ?
                        'http://' :
                        'https://'
                ) .
                $_SERVER['SERVER_NAME'] .
                $_SERVER['CONTEXT_PREFIX'] ?? '' .
                str_replace(
                    $_SERVER['CONTEXT_DOCUMENT_ROOT'] ?? '',
                    '',
                    $path
                );
        } else {
            return false;
        }
    }

    /**
     * Does a URL "exist"?
     *
     * We're going to treat any response code < 400 as existing.
     *
     * @link http://php.net/manual/en/function.get-headers.php#112652 Comment
     *     get_headers() documentation
     * @param string $url
     */
    public static function exists($url)
    {
        $headers = get_headers($url);
        return intval(substr($headers[0], 9, 3)) < 400;
    }
}
