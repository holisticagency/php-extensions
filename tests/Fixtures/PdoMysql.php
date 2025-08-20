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

class PdoMysql implements DbalExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = '';

    public function getName(): string
    {
        return 'pdo_mysql';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getDriverName(): string
    {
        return 'mysql';
    }

    public function getDefaultTcpPort(): ?int
    {
        return 3306;
    }

    public function getDefaultSocket(): ?string
    {
        return \null;
    }

    public function getInfoModuleBlocks(): int
    {
        return 3;
    }

    public function setFromInfoModule(array $directives): self
    {
        // "Client API version => mysqlnd 8.4.10"
        $extension = preg_grep(',Client API version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->clientVersion = $ext_version;

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
        return [
            // 'host' => ['mysqli.default_host'],
            // 'port' => ['mysqli.default_port'],
            // 'user' => ['mysqli.default_user'],
            // 'password' => ['mysqli.default_pw'],
            'socket' => ['pdo_mysql.default_socket'],
        ];
    }

    public function getEnvVars(): array
    {
        return [
            // 'MYSQL_ROOT_PASSWORD', 'MARIADB_ROOT_PASSWORD',
            'host' => ['MYSQL_HOST'],
            'port' => ['MYSQL_TCP_PORT'],
            'user' => ['MYSQL_USER', 'MARIADB_USER'],
            'password' => ['MYSQL_PWD', 'MYSQL_PASSWORD', 'MARIADB_PASSWORD'],
            'socket' => ['MYSQL_UNIX_PORT'],
            'database' => ['MYSQL_DATABASE', 'MARIADB_DATABASE'],
        ];
    }
}
