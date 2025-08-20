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

namespace HolisticAgency\Test\PhpExtensions;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use HolisticAgency\PhpExtensions\ExtensionManager;
use HolisticAgency\PhpExtensions\NotFoundException;
use HolisticAgency\PhpExtensions\ExtensionInterface;
use HolisticAgency\Test\PhpExtensions\Fixtures\Dummy\DummyPhpInfoModules;
use HolisticAgency\Test\PhpExtensions\Fixtures\JsonExtension;
use HolisticAgency\Test\PhpExtensions\Fixtures\LdapExtensionInterface;

#[CoversClass(ExtensionManager::class)]
class ExtensionManagerTest extends TestCase
{
    private ExtensionManager $em;

    protected function setUp(): void
    {
        $this->em = new ExtensionManager(new DummyPhpInfoModules());
    }

    public function testAdd(): void
    {
        // Given
        $expectedJsonVersion = '8.4.10'; // \phpversion() ?: '';

        // When
        $this->em->add(new JsonExtension());

        // Then
        $this->assertEquals($expectedJsonVersion, $this->em->get('json')->getVersion());
        $this->assertEquals(['json'], $this->em->keys());
    }

    public function testMissingExtension(): void
    {
        // Given
        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('Extension "json" not managed');

        // When
        $this->em->get('json');

        // Then
        // An exception is thrown
    }

    public function testFilter(): void
    {
        // Given
        $this->em->add(new JsonExtension());

        // When
        $found = $this->em->filter(fn(ExtensionInterface $e) => 'json' == $e->getName());
        $notFound = $this->em->filter(fn(ExtensionInterface $e) => $e instanceof LdapExtensionInterface);

        // Then
        $this->assertCount(1, $found);
        $this->assertCount(0, $notFound);
    }
}
