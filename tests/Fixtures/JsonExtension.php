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

class JsonExtension implements ExtensionInterface
{
    private string $clientVersion = '8.4.10';

    private string $libVersion = '';

    public function getName(): string
    {
        return 'json';
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
        // $this->clientVersion = \phpversion($this->getName()) ?: '';

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
