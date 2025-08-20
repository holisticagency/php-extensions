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

class Ldap implements LdapExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = '';

    private string $vendor = '';

    private bool $sasl = \false;

    public function getName(): string
    {
        return 'ldap';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getInfoModuleBlocks(): int
    {
        return 3;
    }

    public function setFromInfoModule(array $directives): self
    {
        // "API Version => 3001"
        $extension = preg_grep(',API Version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->clientVersion = $ext_version;
        // "Vendor Name => OpenLDAP"
        $extension = preg_grep(',Vendor Name,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace(',[^=]+=> ,', '', $extension) ?? '';
        /* @var string $extension */
        $this->vendor = $ext_version;
        // "Vendor Version => 20610"
        $extension = preg_grep(',Vendor Version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->libVersion = $ext_version;
        // "SASL Support => Enabled"
        $extension = preg_grep(',SASL Support,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $sasl = preg_replace(',[^=]+=> ,', '', $extension) ?? '';
        $this->sasl = 'enabled' == \strtolower($sasl);

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

    public function getVendor(): string
    {
        return $this->vendor;
    }

    public function hasSasl(): bool
    {
        return $this->sasl;
    }
}
