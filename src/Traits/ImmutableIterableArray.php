<?php

namespace Battis\DataUtilities\Traits;

use Exception;

trait ImmutableIterableArray
{
    use IterableArray;

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception(static::class . ' is immmutable.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new Exception(static::class . ' is immmutable.');
    }
}
