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

namespace HolisticAgency\Test\PhpExtensions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use HolisticAgency\PhpExtensions\LocalCache;

#[CoversClass(LocalCache::class)]
class LocalCacheTest extends TestCase
{
    private LocalCache $cache;

    protected function setUp(): void
    {
        $this->cache = new LocalCache([
            'php.extensions' => __DIR__ . '/Fixtures/cache/php.extensions.cache',
            'managed.extensions' => __DIR__ . '/Fixtures/cache/managed.extensions.cache',
        ]);
    }

    public function testClear(): void
    {
        // Given
        copy(__DIR__ . '/Fixtures/Stub/managed.extensions.cache', __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        file_exists(__DIR__ . '/Fixtures/cache/php.extensions.cache') && unlink(__DIR__ . '/Fixtures/cache/php.extensions.cache');

        // When
        $actual = $this->cache->clear();

        // Then
        $this->assertTrue($actual);
    }

    public function testDelete(): void
    {
        // Given
        touch(__DIR__ . '/Fixtures/cache/php.extensions.cache');

        // When
        $actual = $this->cache->delete('php.extensions');
        \clearstatcache(true, __DIR__ . '/Fixtures/cache/php.extensions.cache');
        $actualSecond = $this->cache->delete('php.extensions');

        // Then
        $this->assertTrue($actual);
        $this->assertFalse($actualSecond);
    }

    public function testGet(): void
    {
        // Given
        copy(__DIR__ . '/Fixtures/Stub/managed.extensions.cache', __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        \clearstatcache(true, __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        $this->cache = new LocalCache([
            'php.extensions' => __DIR__ . '/Fixtures/cache/php.extensions.cache',
            'managed.extensions' => __DIR__ . '/Fixtures/cache/managed.extensions.cache',
        ]);

        // When
        $actual = $this->cache->get('managed.extensions');

        // Then
        /* @phpstan-ignore argument.type */
        $this->assertArrayHasKey('json', $actual);
    }

    public function testGetMiss(): void
    {
        // Given
        copy(__DIR__ . '/Fixtures/Stub/managed.extensions.cache', __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        touch(__DIR__ . '/Fixtures/cache/managed.extensions.cache', time() - ($this->cache::TTL + 1));
        \clearstatcache(true, __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        $this->cache = new LocalCache([
            'php.extensions' => __DIR__ . '/Fixtures/cache/php.extensions.cache',
            'managed.extensions' => __DIR__ . '/Fixtures/cache/managed.extensions.cache',
        ]);

        // When
        $actual = $this->cache->get('managed.extensions');

        // Then
        $this->assertNull($actual);
        $this->assertFileDoesNotExist(__DIR__ . '/Fixtures/cache/managed.extensions.cache');
    }

    public function testHas(): void
    {
        // Given
        copy(__DIR__ . '/Fixtures/Stub/managed.extensions.cache', __DIR__ . '/Fixtures/cache/managed.extensions.cache');
        file_exists(__DIR__ . '/Fixtures/cache/php.extensions.cache') && unlink(__DIR__ . '/Fixtures/cache/php.extensions.cache');
        $this->cache = new LocalCache([
            'php.extensions' => __DIR__ . '/Fixtures/cache/php.extensions.cache',
            'managed.extensions' => __DIR__ . '/Fixtures/cache/managed.extensions.cache',
        ]);

        // When
        $actual = $this->cache->has('managed.extensions');
        $actualSecond = $this->cache->has('php.extensions');

        // Then
        $this->assertTrue($actual);
        $this->assertFalse($actualSecond);
    }

    public function testSet(): void
    {
        // Given
        $value = unserialize(file_get_contents(__DIR__ . '/Fixtures/Stub/managed.extensions.cache') ?: '');

        // When
        $actual = $this->cache->set('managed.extensions', $value);
        $actualSecond = $this->cache->set('wrong.key', $value);
        $actualThird = $this->cache->set('managed.extensions', $value, new \DateInterval('P7D'));

        // Then
        $this->assertTrue($actual);
        $this->assertFalse($actualSecond);
        $this->assertTrue($actualThird);
    }
}
