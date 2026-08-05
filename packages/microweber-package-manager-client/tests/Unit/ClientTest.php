<?php

declare(strict_types=1);

namespace MicroweberPackages\PackageManagerClient\Tests\Unit;

use MicroweberPackages\PackageManagerClient\Client;
use MicroweberPackages\PackageManagerClient\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClientTest extends TestCase
{
    #[Test]
    public function it_sets_package_servers_and_licenses(): void
    {
        $client = new Client(['http://127.0.0.1:9/packages.json'], ['verify_ssl' => false]);
        $client->setLicenses([['local_key' => 'ABC']]);
        $client->addLicense('DEF');

        $this->assertCount(1, $client->packageServers);
        $this->assertCount(2, $client->licenses);

        $headers = $client->prepareHeaders();
        $this->assertNotEmpty($headers);
        $auth = array_values(array_filter($headers, static fn (string $h): bool => str_starts_with($h, 'Authorization:')));
        $this->assertCount(1, $auth);
    }

    #[Test]
    public function it_returns_empty_on_unreachable_server(): void
    {
        $client = new Client(['http://127.0.0.1:1/packages.json'], [
            'timeout' => 1,
            'connect_timeout' => 1,
            'verify_ssl' => false,
        ]);

        $result = $client->search();
        $this->assertIsArray($result);
    }
}
