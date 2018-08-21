<?php

namespace Opdavies\Tests\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Service\Partials;
use PHPUnit\Framework\TestCase;

class PartialsTest extends TestCase
{
    /**
     * Test loading partials from multiple partial files.
     */
    public function testLoadingFiltersFromPartials()
    {
        /** @var Filter[] $filters */
        $filters = FakePartials::load('filters');

        $this->assertCount(3, $filters);

        $this->assertSame('foo@example.com', $filters[0]->getProperties()['from'][0]);
        $this->assertSame('bar@example.com', $filters[1]->getProperties()['from'][0]);
        $this->assertSame('baz@example.com', $filters[2]->getProperties()['from'][0]);
    }
}

class FakePartials extends Partials
{
    /**
     * {@inheritdoc}
     */
    protected function getFilePattern($directoryName)
    {
        return __DIR__ . '/../../stubs/filters/*.php';
    }
}
