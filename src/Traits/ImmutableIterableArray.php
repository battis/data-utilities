<?php

namespace Battis\DataUtilities\Traits;

use Exception;

/**
 * @template TKey
 * @template TInternal
 * @template TExternal
 */
trait ImmutableIterableArray
{
    /** @use IterableArray<TKey, TInternal, TExternal> */
    use IterableArray;

    /**
     * @param TKey $offset
     * @param TExternal $value
     * @return void
     * @throws Exception
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new Exception(static::class . ' is immmutable.');
    }

    /**
     * @param TKey $offset
     * @return void
     * @throws Exception
     */
    public function offsetUnset(mixed $offset): void
    {
        throw new Exception(static::class . ' is immmutable.');
    }
}
