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
            '/foo/bar/baz.txt' => ['/foo/bar/baz.txt', '/argle/bargle/'],
            '/argle/bargle/foo/bar/baz.unslash' => ['foo/bar/baz.unslash', '/argle/bargle'],
            '/argle/bar/baz.dots' => ['../foo/../bar/baz.dots', '/argle/bargle'],
            ':argle:bargle:foo:bar:baz.posix' => ['foo:bar:baz.posix', ':argle:bargle', ':'],
            '/argle/bargle/:/foo/bar/baz.mismatch' => ['/foo/bar/baz.mismatch', '/argle/bargle/', ':'],
            '\argle\foo\baz.windows' => ['..\foo\bar\..\baz.windows', '\argle\bargle', '\\'],
            '/baz.lots' => ['/foo/bar/../../../../../../../../../../baz.lots', '/argle/bargle'],
            'argle/bargle/foo/bar/baz.rel' => ['foo/bar/baz.rel', 'argle/bargle'],
            'baz.lots' => ['foo/bar/../../../../../../baz.lots'],
        ];

        foreach ($values as $expected => $args) {
            $this->assertEquals($expected, Path::canonicalize(...$args));
        }
    }

    public function testJoin()
    {
        $values = [
            'a/b/c' => ['a','b','c'],
            'd/e/f/' => ['d/', 'e/', 'f/'],
            '/g/h/i' => ['/g','/h', '/i'],
            '/l/m/n/o/' => ['/l', 'm/', '/n', 'o/'],
            '/p/q/r/s/' => ['/p/', '/q/', '/r/', '/s/'],
            't/u//v' => ['t', 'u//v'],
            'w/../x/./y/z' => ['w', '../x', '.', 'y/', '/z'],
            'A/B/C/D/E' => [['A','B'],'C',['D', 'E']],
            "F\\G\\H" => ["\\", ["F","G","H"]],
            'fooIfooJfooKfooL/Mfoo' => ['foo', [['fooIfoo', 'fooJ'],'fooKfoo', ['L/Mfoo']]],
            "N/O/P" => ['N', null, 'O', null, null, 'P'],
            "Q/R/S" => ['Q', [], 'R', [], [], 'S'],
            "T/U/V" => ['T', null, 'U', [], 'V'],
            "W\\X\\Y\\Z" => ["\\", [['W', 'X', null],[],['Y', 'Z', null]]]
        ];

        foreach ($values as $expected => $args) {
            $this->assertEquals($expected, Path::join(...$args));
        }
    }
}
