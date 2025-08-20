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
use HolisticAgency\PhpExtensions\PhpInfoModulesInterface;

class DummyPhpInfoModules implements PhpInfoModulesInterface
{
    public function getCache(): CacheInterface
    {
        return new DummyCache();
    }

    /**
     * @return string[]
     */
    public function search(string $extension, int $emptyLines): array
    {
        return [];
    }
}
