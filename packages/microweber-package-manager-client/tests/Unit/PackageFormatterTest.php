<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Unit;

use MicroweberPackages\PackageManagerClient\Contracts\LocalPackageResolverInterface;
use MicroweberPackages\PackageManagerClient\PackageFormatter;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PackageFormatterTest extends TestCase
{
    #[Test]
    public function it_marks_paid_license_packages(): void
    {
        $formatted = PackageFormatter::format([
            'name' => 'microweber-modules/premium',
            'type' => 'microweber-module',
            'version' => '1.0.0',
            'target-dir' => 'Premium',
            'dist' => ['type' => 'license_key', 'url' => ''],
            'extra' => ['whmcs' => ['buy_link' => 'https://buy.example']],
        ]);

        $this->assertTrue($formatted['is_paid']);
        $this->assertFalse($formatted['available_for_install']);
        $this->assertSame('https://buy.example', $formatted['buy_link']);
    }

    #[Test]
    public function it_detects_updates_against_local_packages(): void
    {
        $resolver = new class implements LocalPackageResolverInterface {
            public function modules(): array
            {
                return [[
                    'name' => 'sample',
                    'dir_name' => 'SampleHello',
                    'version' => '1.0.0',
                ]];
            }

            public function templates(): array
            {
                return [];
            }
        };

        $formatted = PackageFormatter::format([
            'name' => 'microweber-modules/sample-hello',
            'type' => 'microweber-module',
            'version' => '2.0.0',
            'target-dir' => 'SampleHello',
            'dist' => ['type' => 'zip', 'url' => 'https://example.com/a.zip'],
        ], $resolver);

        $this->assertTrue($formatted['has_update']);
        $this->assertNotFalse($formatted['current_install']);
    }
}
