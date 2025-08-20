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

class Pgsql implements DbalExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = '';

    public function getName(): string
    {
        return 'pgsql';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getDriverName(): string
    {
        return 'postgres';
    }

    public function getDefaultTcpPort(): ?int
    {
        return 5432;
    }

    public function getDefaultSocket(): ?string
    {
        return '/tmp/.s.PGSQL.5432';
    }

    public function getInfoModuleBlocks(): int
    {
        return 3;
    }

    public function setFromInfoModule(array $directives): self
    {
        $this->clientVersion = \phpversion($this->getName()) ?: '';
        // "PostgreSQL (libpq) Version => 17.5"
        $extension = preg_grep(',PostgreSQL \([^\)]+\) Version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->libVersion = $ext_version;

        return $this;
    }

    public function isPdo(): bool
    {
        return \false;
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
        return [
            'host' => ['PGHOST'],
            'port' => ['PGPORT'],
            'user' => ['PGUSER'],
            'password' => ['PGPASSWORD'],
            'socket' => [],
            'database' => ['PGDATABASE'],
        ];
    }
}
