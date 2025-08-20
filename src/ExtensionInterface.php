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
 * @author JamesRezo <james@rezo.net>
 */
interface ExtensionInterface
{
    public function getName(): string;

    /**
     * @return string[]
     */
    public function getAliases(): array;

    public function getVersion(): string;

    /**
     * Defaults to empty string if not initialized or if no info alvailable
     */
    public function getLibVersion(): string;

    /**
     * @param string[] $directives
     */
    public function setFromInfoModule(array $directives): self;

    /**
     * @return int Number of directive blocks in phpinfo(). If set to 0, there is no need to call phpinfo()
     */
    public function getInfoModuleBlocks(): int;
}
