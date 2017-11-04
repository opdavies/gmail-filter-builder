<?php

use Opdavies\GmailFilterBuilder\Builder;
use Opdavies\GmailFilterBuilder\Filter;

class BuilderTest extends \PHPUnit_Framework_TestCase
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
