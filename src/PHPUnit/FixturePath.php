<?php

namespace Battis\DataUtilities\PHPUnit;

use ReflectionClass;

trait FixturePath
{
    private ?string $_fixturePath = null;

    protected function initFixturePath(string $path = null) {
        if ($path === null) {
            $path = (new ReflectionClass(self::class))->getFileName();
            $path = preg_replace('@^(.*' . DIRECTORY_SEPARATOR . 'tests)(' . DIRECTORY_SEPARATOR . '.*)$$@', '$1/Fixtures$2', $path);
            $path = dirname($path) . DIRECTORY_SEPARATOR . basename($path, '.php');
        }
        $this->_fixturePath = $path;
    }

    private function getFixturePath(): string
    {
        if ($this->_fixturePath === null) {
            $this->initFixturePath();
        }
        return $this->_fixturePath;
    }

    final protected function getPathToFixture(string $pathToFileRelativeToFixturePath): string
    {
        return $this->getFixturePath() . DIRECTORY_SEPARATOR . $pathToFileRelativeToFixturePath;
    }
}
