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

namespace HolisticAgency\Test\PhpExtensions\Fixtures;

use HolisticAgency\PhpExtensions\ExtensionInterface;

/**
 * @internal
 *
 * @author JamesRezo <james@rezo.net>
 */
interface LdapExtensionInterface extends ExtensionInterface
{
    public function getVendor(): string;

    public function hasSasl(): bool;
}
