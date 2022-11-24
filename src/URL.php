<?php

namespace Battis\DataUtilities;

class URL
{
    /**
     * Generate a URL from a path
     *
     * @param string $path A valid file path
     * @param string[] $vars Optional (default: `$_SERVER` unless run from CLI,
     *                       in which case the method fails without this
     *                       parameter). Array must have keys `HTTPS,
     *                       SERVER_NAME, CONTEXT_PREFIX,
     *                       CONTEXT_DOCUMENT_ROOT`.
     * @param string|false $basePath The base path from which to start when
     *                               processing a relative path.
     * @return false|string The URL to that path, or `false` if the URl cannot
     *     be computed (e.g. if run from CLI)
     */
    public static function fromPath($path, array $vars = null, $basePath = false)
    {
        if ($vars === null) {
            if (php_sapi_name() != 'cli') {
                $vars = $_SERVER;
            } else {
                return false;
            }
        }

        if ($basePath !== false && substr($path, 0, 1) !== '/') {
            $basePath = rtrim($basePath, '/');
            $path = realpath("$basePath/$path");
        }

        if (realpath($path)) {
            return (!isset($vars['HTTPS']) || $vars['HTTPS'] != 'on' ?
                        'http://' :
                        'https://'
                ) .
                $vars['SERVER_NAME'] .
                $vars['CONTEXT_PREFIX'] ?? '' .
                str_replace(
                    $vars['CONTEXT_DOCUMENT_ROOT'] ?? '',
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
