<?php

namespace Battis\DataUtilities\Tests;

use Battis\DataUtilities\URL;
use PHPUnit\Framework\TestCase;

class URLTest extends TestCase
{
    public function testFromPath()
    {
        $host = 'example.com';
        $docRoot = '/var/www';
        $server = [
            'HTTPS' => 'on',
            'SERVER_NAME' => $host,
            'DOCUMENT_ROOT' => $docRoot,
            'CONTEXT_PREFIX' => null,
        ];

        $values = [
            [
                [],
                ["$docRoot/foo/bar/baz.html"],
                "https://$host/foo/bar/baz.html",
            ],
            [
                [],
                ['../../baz.html', "$docRoot/foo/bar"],
                "https://$host/baz.html",
            ],
            [
                [
                    'HTTPS' => 'off',
                ],
                ['/foo/bar/biz.html'],
                "http://$host/foo/bar/biz.html",
            ],
            [
                [
                    'DOCUMENT_ROOT' => '/home/alice/public_html',
                    'CONTEXT_PREFIX' => '~alice/',
                ],
                ['/home/alice/public_html/foo/bar/buz.html'],
                "https://$host/~alice/foo/bar/buz.html",
            ],
        ];

        foreach($values as [$diff, $args, $expected]) {
            $_SERVER = array_merge($server, $diff);
            $this->assertEquals($expected, URL::fromPath(...$args));
        }
    }

    public function testExists()
    {
        $this->assertTrue(URL::exists('https://raw.githubusercontent.com/battis/data-utilities/main/README.md'));
        $this->assertFalse(URL::exists('https://raw.githubusercontent.com/battis/data-utilities/main/a-file-that-will-never-exist.xyz'));
    }
}
