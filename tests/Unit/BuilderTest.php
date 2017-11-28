<?php

use Opdavies\GmailFilterBuilder\Builder;
use Opdavies\GmailFilterBuilder\Filter;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testBuild()
    {
        $filterA = $this->getMockBuilder(Filter::class)
            ->setMethods(['getProperties'])
            ->getMock();

        $filterB = $this->getMockBuilder(Filter::class)
            ->setMethods(['getProperties'])
            ->getMock();

        $filterA->method('getProperties')
            ->willReturn(
                [
                    ['from' => 'foo@example.com'],
                    ['shouldStar' => true],
                ]
            );

        $filterB->method('getProperties')
            ->willReturn(
                [
                    ['to' => 'bar@example.com'],
                ]
            );

        $builder = new Builder([
            $filterA->getProperties(),
            $filterB->getProperties(),
        ]);
        $builder->build();
    }
}
