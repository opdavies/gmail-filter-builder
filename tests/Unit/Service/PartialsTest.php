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
        $this->assertSame(['foo@example.com'], $filters[0]->getConditions()['from']);
        $this->assertSame(['bar@example.com'], $filters[1]->getConditions()['from']);
        $this->assertSame(['baz@example.com'], $filters[2]->getConditions()['from']);
    }
}

class FakePartials extends Partials
{
    /**
     * {@inheritdoc}
     */
    protected static function getFilePattern($directoryName): string
    {
        return 'tests/stubs/filters/*.php';
    }
}
