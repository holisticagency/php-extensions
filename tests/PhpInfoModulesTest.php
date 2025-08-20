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
use HolisticAgency\PhpExtensions\PhpInfoModules;
use HolisticAgency\Test\PhpExtensions\Fixtures\Dummy\DummyCache;

#[CoversClass(PhpInfoModules::class)]
class PhpInfoModulesTest extends TestCase
{
    private PhpInfoModules $modules;

    protected function setUp(): void
    {
        $this->modules = new PhpInfoModules(new DummyCache());
    }

    public function testSearch(): void
    {
        // Given
        // When
        $actual = $this->modules->search('json', 2);

        // Then
        $this->assertCount(4, $actual);
    }
}
