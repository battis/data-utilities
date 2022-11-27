<?php

namespace Battis\DataUtilities\Tests\Fixtures\PHPUnit\FixturePathTest;

use Battis\DataUtilities\PHPUnit\FixturePath;

class A {
    use FixturePath;

    public function fixtureInitFixturePath(string $path = null) {
        $this->initFixturePath($path);
    }

    public function fixtureGetPathToFixture(string $pathToFileRelativeToFixturePath): string
    {
        return $this->getPathToFixture($pathToFileRelativeToFixturePath);
    }
}
