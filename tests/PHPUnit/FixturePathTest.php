<?php

namespace Battis\DataUtilities\Tests\PHPUnit;

use Battis\DataUtilities\PHPUnit\FixturePath;
use PHPUnit\Framework\TestCase;

class FixturePathTest extends TestCase
{
    use FixturePath;

    public function testInitFixturePath() {
        $this->initFixturePath('foo');
        $this->assertEquals('foo/bar', $this->getPathToFixture('bar'));

        $dir = realpath(__DIR__ . '/..') . '/baz';
        $this->initFixturePath($dir);
        $this->assertEquals($dir . '/test', $this->getPathToFixture('test'));

        $dir = realpath(__DIR__ . '/..') . '/Fixtures/PHPUnit/FixturePathTest';
        $this->initFixturePath();
        $this->assertEquals($dir . '/test', $this->getPathToFixture('test'));
    }

    public function testGetFixturePath() {
        $base = str_replace('/@@@', '', $this->getPathToFixture('@@@'));
        $this->assertEquals($base . '/test', $this->getPathToFixture('test'));
        $this->assertEquals($base . '/test.php', $this->getPathToFixture('test.php'));
        $this->assertEquals($base . '/foo/bar/baz.php', $this->getPathToFixture('foo/bar/baz.php'));
    }
}
