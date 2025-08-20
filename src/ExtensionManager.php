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
 * @author JamesRezo <james@rezo.net>
 */
final class ExtensionManager implements ManagerInterface
{
    private const KEY = 'managed.extensions';

    private CacheInterface $cache;

    /** @var array<string,ExtensionInterface> */
    private array $extensions;

    public function __construct(private PhpInfoModulesInterface $modules)
    {
        $this->cache = $modules->getCache();
        /** @var array<string,ExtensionInterface> $extensions */
        $extensions = $this->cache->get(self::KEY, []);
        $this->extensions = $extensions;
    }

    public function add(iterable|ExtensionInterface $extensions): self
    {
        if ($extensions instanceof ExtensionInterface) {
            $extensions = [$extensions];
        }

        foreach ($extensions as $extension) {
            $this->doAdd($extension);
        }

        $this->cache->set(self::KEY, $this->extensions);

        return $this;
    }

    public function keys(): array
    {
        return \array_keys($this->extensions);
    }

    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->extensions);
    }

    public function get(string $id): ExtensionInterface
    {
        if (!$this->has($id)) {
            throw new NotFoundException(sprintf('Extension "%s" not managed', $id));
        }

        return $this->extensions[$id];
    }

    private function supports(string $name): bool
    {
        return \extension_loaded($name);
    }

    private function doAdd(ExtensionInterface $extension): void
    {
        if ($this->supports($extension->getName())) {
            $extension->setFromInfoModule(
                $this->modules->search(
                    $extension->getName(),
                    $extension->getInfoModuleBlocks(),
                ),
            );
            $this->extensions[$extension->getName()] = $extension;
        }
    }

    /**
     * @codeCoverageIgnore
     */
    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    public function filter(callable $filter): iterable
    {
        return \array_filter($this->extensions, $filter);
    }
}
