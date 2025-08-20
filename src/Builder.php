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

/**
 * @codeCoverageIgnore
 *
 * @author JamesRezo <james@rezo.net>
 */
final class Builder
{
    public static function createManager(string $phpExtensions, string $managedExtensions): ExtensionManager
    {
        return new ExtensionManager(new PhpInfoModules(new LocalCache([
            'php.extensions' => $phpExtensions,
            'managed.extensions' => $managedExtensions,
        ])));
    }
}
