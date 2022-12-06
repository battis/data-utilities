<?php

namespace Battis\DataUtilities\Tests;

use Battis\DataUtilities\Path;
use PHPUnit\Framework\TestCase;

class PathTest extends TestCase
{
    public function testCanonicalize()
    {
        $values = [
            '/foo/bar/baz.txt' => ['/foo/bar/baz.txt'],
            '/foo/baz.txt' => ['/foo/bar/../baz.txt'],
            '/foo/bar/baz.slashes' => ['/foo/bar//baz.slashes'],
            '/foo/bar/baz.dot' => ['/foo/./bar/baz.dot'],
            '/baz.nest' => ['/foo/bar/../../baz.nest'],
            '/argle/bargle/foo/bar/baz.txt' => ['/foo/bar/baz.txt', '/argle/bargle/'],
            '/argle/bargle/foo/bar/baz.unslash' => ['foo/bar/baz.unslash', '/argle/bargle'],
            '/argle/bar/baz.dots' => ['../foo/../bar/baz.dots', '/argle/bargle'],
            ':argle:bargle:foo:bar:baz.posix' => ['foo:bar:baz.posix', ':argle:bargle', ':'],
            '/argle/bargle/:/foo/bar/baz.mismatch' => ['/foo/bar/baz.mismatch', '/argle/bargle/', ':'],
            '\argle\foo\baz.windows' => ['..\foo\bar\..\baz.windows', '\argle\bargle', '\\'],
            '/baz.lots' => ['/foo/bar/../../../../../../../../../../baz.lots', '/argle/bargle'],
            'argle/bargle/foo/bar/baz.rel' => ['foo/bar/baz.rel', 'argle/bargle'],
            'baz.lots' => ['foo/bar/../../../../../../baz.lots']
        ];

        foreach ($values as $expected => $args) {
            $this->assertEquals($expected, Path::canonicalize(...$args));
        }
    }
}
