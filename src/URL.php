<?php

namespace Battis\DataUtilities;

class URL
{
    public static function fromPath(string $path, ?string $base = null, string $separator = DIRECTORY_SEPARATOR): string
    {
        $path = preg_replace("@$separator@", '/', Path::canonicalize($path, $base, $separator));
        return
            'http' . (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://' .
            $_SERVER['SERVER_NAME'] .
            Path::canonicalize(preg_replace("@^{$_SERVER['DOCUMENT_ROOT']}@", '', $path), '/' . preg_replace('@^/@', '', $_SERVER['CONTEXT_PREFIX'] ?? ''), '/');
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
