<?php

declare(strict_types=1);

/*
 * This file is part of holistic-agency/php-extensions.
 *
 * (c) JamesRezo <james@rezo.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace HolisticAgency\Test\PhpExtensions\Fixtures\Dummy;

use Psr\SimpleCache\CacheInterface;

class DummyCache implements CacheInterface
{
    public function clear(): bool
    {
        return true;
    }

    public function delete(string $key): bool
    {
        return true;
    }

    public function deleteMultiple(iterable $keys): bool
    {
        return true;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $default;
    }

    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return empty($keys) ? [] : [array_pop($keys) => $default];
    }

    public function has(string $key): bool
    {
        return false;
    }

    public function set(string $key, mixed $value, int|\DateInterval|null $ttl = null): bool
    {
        return true;
    }

    /**
     * @param array<mixed> $values
     */
    public function setMultiple(iterable $values, int|\DateInterval|null $ttl = null): bool
    {
        return true;
    }
}
