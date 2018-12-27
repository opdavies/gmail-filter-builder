<?php

namespace Opdavies\Tests\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Service\Addresses;
use PHPUnit\Framework\TestCase;

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
}

class FakeAddresses extends Addresses
{
    /**
     * {@inheritdoc}
     */
    protected function getDirectoryPaths()
    {
        return collect(__DIR__ . '/../../stubs/addresses/');
    }
}
