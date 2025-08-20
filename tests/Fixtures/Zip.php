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

class Zip implements ExtensionInterface
{
    private string $clientVersion = '';

    private string $libVersion = '';

    public function getName(): string
    {
        return 'zip';
    }

    public function getAliases(): array
    {
        return [];
    }

    public function getInfoModuleBlocks(): int
    {
        return 3; // 0 no need of INFO_MODULES
    }

    public function setFromInfoModule(array $directives): self
    {
        // "Zip version => 1.22.6"
        $extension = preg_grep(',^Zip version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->clientVersion = $ext_version;
        // "Libzip version => 1.11.4"
        $extension = preg_grep(',^Libzip version,i', $directives) ?: [];
        $extension = array_pop($extension);
        /** @var string $extension */
        $ext_version = preg_replace([',[^=]+=> ,', ',[^\d\.]+,'], ['', ''], $extension) ?? '';
        $this->libVersion = $ext_version;

        return $this;
    }

    public function getVersion(): string
    {
        // extension_loaded('zip') == true
        if ($this->clientVersion == '') {
            $r = new \ReflectionExtension($this->getName());
            $this->clientVersion = $r->getVersion();
        }

        return $this->clientVersion;
    }

    public function getLibVersion(): string
    {
        // extension_loaded('zip') == true
        // ext-version >= 1.16.0
        if ($this->libVersion == '') {
            $r = new \ReflectionClass('ZipArchive');
            if (\in_array('LIBZIP_VERSION', $r->getConstants(), true)) {
                $this->libVersion = \ZipArchive::LIBZIP_VERSION;
            }
        }

        return $this->libVersion;
    }
}
