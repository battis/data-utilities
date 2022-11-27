<?php

namespace Battis\DataUtilities\Tests\PHPUnit;

use Battis\DataUtilities\Tests\Fixtures\PHPUnit\FixturePathTest\A;
use Battis\DataUtilities\Tests\Fixtures\PHPUnit\FixturePathTest\B;
use PHPUnit\Framework\TestCase;

class FixturePathTest extends TestCase
{
    public function testInitFixturePath() {
        $values = [
            ['foo', 'bar', 'foo/bar'],
            [realpath(__DIR__ . '/..') . '/baz', 'test', realpath(__DIR__ . '/..') . '/baz/test'],
            [null, 'test', realpath(__DIR__ . '/..') . '/Fixtures/Fixtures/PHPUnit/FixturePathTest/A/test']
        ];

        foreach ($values as list($initArg, $getArg, $expected)) {
            $a = new A();
            if ($initArg === null) {
                $a->fixtureInitFixturePath();
            } else {
                $a->fixtureInitFixturePath($initArg);
            }
            $this->assertEquals($expected, $a->fixtureGetPathToFixture($getArg));
        }

        $b = new B();
        $this->assertEquals(realpath(__DIR__ . '/..') . '/Fixtures/Fixtures/PHPUnit/FixturePathTest/B/test', $b->fixtureGetPathToFixture('test'));
    }

    public function testGetFixturePath() {
        $a = new A();
        $base = str_replace('/@@@', '', $a->fixtureGetPathToFixture('@@@'));

        $values = [
            ['test', $base . '/test'],
            ['test.php', $base . '/test.php'],
            ['foo/bar/baz.php', $base . '/foo/bar/baz.php'],
            ['/foo/bar.php', '/foo/bar.php']
        ];

        foreach ($values as list($arg, $expected)) {
            $this->assertEquals($expected, $a->fixtureGetPathToFixture($arg));
        }
    }
}
