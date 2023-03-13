<?php

namespace Battis\DataUtilities\PHPUnit;

use ReflectionClass;

// TODO does DIRECTORY_SEPARATOR need to be escaped in regex?
trait FixturePath
{
    private ?string $_fixturePath = null;

    protected function initFixturePath(string $path = null) {
        if ($path === null) {
            $path = (new ReflectionClass(static::class))->getFileName();
            $path = preg_replace(
                '@^(.*' . DIRECTORY_SEPARATOR . 'tests)(' . DIRECTORY_SEPARATOR . '.*)$$@',
                '$1' . DIRECTORY_SEPARATOR . 'Fixtures$2',
                $path
            );
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
        if (substr($pathToFileRelativeToFixturePath, 0, 1) === DIRECTORY_SEPARATOR) {
            return $pathToFileRelativeToFixturePath;
        }
        return $this->getFixturePath() . DIRECTORY_SEPARATOR . $pathToFileRelativeToFixturePath;
    }
}
