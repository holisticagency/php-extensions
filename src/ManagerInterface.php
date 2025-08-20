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

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * @author JamesRezo <james@rezo.net>
 */
interface ManagerInterface extends ContainerInterface
{
    public function getCache(): CacheInterface;

    /**
     * @param ExtensionInterface|ExtensionInterface[] $extensions
     */
    public function add(iterable|ExtensionInterface $extensions): self;

    /**
     * @return string[]
     */
    public function keys(): array;

    /**
     * @param callable(ExtensionInterface):bool $filter
     *
     * @return array<string,ExtensionInterface>
     */
    public function filter(callable $filter): iterable;
}
