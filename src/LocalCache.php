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

namespace HolisticAgency\PhpExtensions;

use Psr\SimpleCache\CacheInterface;

/**
 * @internal
 *
 * @author JamesRezo <james@rezo.net>
 */
final class LocalCache implements CacheInterface
{
    public const TTL = 604800; // 1 week (P7D)

    /** @var string[] */
    private const ALLOWED_KEYS = ['php.extensions', 'managed.extensions'];

    /** @var array<string,int> */
    private array $ttl = [];

    /**
     * @var array<string,string>
     */
    private array $cacheFiles;

    /**
     * @param array<string,string> $cacheFiles
     */
    public function __construct(array $cacheFiles)
    {
        $this->cacheFiles = $this->filter($cacheFiles);

        $this->warmUp();
    }

    /**
     * @param array<string,string> $cacheFiles
     *
     * @return array<string,string>
     */
    private function filter(array $cacheFiles): array
    {
        return \array_filter(
            $cacheFiles,
            $this->isKeyAllowed(...),
            \ARRAY_FILTER_USE_KEY,
        );
    }

    private function warmUp(): void
    {
        foreach ($this->cacheFiles as $key => $filename) {
            if (\file_exists($filename)) {
                $this->ttl[$key] = self::TTL;
            }
        }
    }

    private function isKeyAllowed(string $key): bool
    {
        return \in_array($key, self::ALLOWED_KEYS, true);
    }

    public function clear(): bool
    {
        $ok = true;

        foreach (array_values($this->cacheFiles) as $filename) {
            if (file_exists($filename)) {
                $ok = $ok && unlink($filename);
            }
        }
        $this->ttl = [];

        return $ok;
    }

    public function delete(string $key): bool
    {
        if ($this->isKeyAllowed($key) && file_exists($this->cacheFiles[$key])) {
            unset($this->ttl[$key]);

            return (bool) unlink($this->cacheFiles[$key]);
        }

        return false;
    }

    /**
     * @codeCoverageIgnore
     */
    public function deleteMultiple(iterable $keys): bool
    {
        // if (array_filter(array_values($keys), fn ($key) => !(is_string($key) && in_array($key, self::ALLOWED_KEYS)))) {
        //     // throw new Psr\SimpleCache\InvalidArgumentException('');
        //     return false;
        // }

        return true;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        if ($this->isKeyAllowed($key)) {
            if (file_exists($this->cacheFiles[$key])) {
                \clearstatcache(true, $this->cacheFiles[$key]);
                if ((\time() - \filemtime($this->cacheFiles[$key])) < $this->ttl[$key]) {
                    // TODO check type of unserialized
                    //     $modules = \unserialize(\file_get_contents($this->cacheFiles[$key]) ?: '');
                    //     if (!$modules instanceof \ArrayObject) {
                    //         throw new \RuntimeException('Erreur de deserialisation de cache');
                    //     }
                    //     return $modules;
                    return unserialize(file_get_contents($this->cacheFiles[$key]) ?: '');
                }
                $this->delete($key);
            }
        }

        return $default;
    }

    /**
     * @codeCoverageIgnore
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return [];
    }

    public function has(string $key): bool
    {
        return \array_key_exists($key, $this->ttl);
    }

    public function set(string $key, mixed $value, int|\DateInterval|null $ttl = null): bool
    {
        $ttl = $this->normalizeTtl($ttl);

        if ($this->isKeyAllowed($key)) {
            $this->ttl[$key] = $ttl;
            // TODO memoize type of $value
            file_put_contents($this->cacheFiles[$key], serialize($value)); // : int=size or false

            return true;
        }

        return false;
    }

    private function normalizeTtl(int|\DateInterval|null $ttl): int
    {
        $ttl ??= self::TTL;

        if ($ttl instanceof \DateInterval) {
            $ttl = (new \DateTimeImmutable('@0'))->add($ttl)->getTimestamp();
        }

        return $ttl;
    }

    /**
     * @codeCoverageIgnore
     *
     * @param array<mixed> $values
     */
    public function setMultiple(iterable $values, int|\DateInterval|null $ttl = null): bool
    {
        $ok = true;

        foreach ($values as $key => $value) {
            if (is_string($key)) {
                $ok = $ok && $this->set($key, $value, $ttl);
                continue;
            }

            $ok = false;
        }

        return $ok;
    }
}
