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

class PdoSqlite implements DbalExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = '';

    public function getName(): string
    {
        return 'pdo_sqlite';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getDriverName(): string
    {
        return 'sqlite';
    }

    public function getDefaultTcpPort(): ?int
    {
        return \null;
    }

    public function getDefaultSocket(): ?string
    {
        return \null;
    }

    public function getInfoModuleBlocks(): int
    {
        return 2;
    }

    public function setFromInfoModule(array $directives): self
    {
        $this->clientVersion = \phpversion($this->getName()) ?: '';
        // SQLite Library => 3.50.3
        $extension = preg_grep(',SQLite Library,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->libVersion = $ext_version;

        return $this;
    }

    public function isPdo(): bool
    {
        return \true;
    }

    public function getVersion(): string
    {
        return $this->clientVersion;
    }

    public function getLibVersion(): string
    {
        return $this->libVersion;
    }

    public function getIniParameters(): array
    {
        return [];
    }

    public function getEnvVars(): array
    {
        return [];
    }
}
