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

class Sodium implements ExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = ''; // \SODIUM_LIBRARY_VERSION may not exist;

    public function getName(): string
    {
        return 'sodium';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getInfoModuleBlocks(): int
    {
        return 2;
    }

    public function setFromInfoModule(array $directives): self
    {
        $this->clientVersion = \phpversion($this->getName()) ?: '';
        $extension = preg_grep(',libsodium library version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->libVersion = $ext_version;

        return $this;
    }

    public function getVersion(): string
    {
        return $this->clientVersion;
    }

    public function getLibVersion(): string
    {
        return $this->libVersion;
    }
}
