<?php

namespace Opdavies\Tests\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Service\Partials;
use PHPUnit\Framework\TestCase;

class PartialsTest extends TestCase
{
    /** @test */
    public function filters_can_be_loaded_from_partials()
    {
        /** @var Filter[] $filters */
        $filters = FakePartials::load('filters');

        $this->assertCount(3, $filters);

        $this->assertSame('foo@example.com', $filters[0]->toArray()['from'][0]);
        $this->assertSame('bar@example.com', $filters[1]->toArray()['from'][0]);
        $this->assertSame('baz@example.com', $filters[2]->toArray()['from'][0]);
    }
}

class FakePartials extends Partials
{
    /**
     * {@inheritdoc}
     */
    protected static function getFilePattern($directoryName): string
    {
        return __DIR__ . '/../../stubs/filters/*.php';
    }
}
