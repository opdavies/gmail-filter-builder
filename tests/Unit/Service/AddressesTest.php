<?php

namespace Opdavies\Tests\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Exception\PartialNotFoundException;
use Opdavies\GmailFilterBuilder\Service\Addresses;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;

/**
 * Test loading addresses from a separate file.
 *
 * @coversDefaultClass \Opdavies\GmailFilterBuilder\Service\Addresses
 */
class AddressesTest extends TestCase
{
    /**
     * @covers ::load
     */
    public function testLoad()
    {
        $this->assertEquals([
            'foo@example.com',
            'bar@example.com'
        ], FakeAddresses::load());
    }

    /** @test */
    public function throw_an_exception_if_an_address_file_does_not_exist()
    {
        $this->expectException(PartialNotFoundException::class);

        Addresses::load('does-not-exist');
    }
}

class FakeAddresses extends Addresses
{
    /**
     * {@inheritdoc}
     */
    protected function getDirectoryPaths(): Collection
    {
        return collect(__DIR__ . '/../../stubs/addresses/');
    }
}
