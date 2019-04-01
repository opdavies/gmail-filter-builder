<?php

namespace Tests\Opdavies\GmailFilterBuilder;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Model\FilterGroup;
use PHPUnit\Framework\TestCase;

class OtherwiseHasTest extends TestCase
{

    /** @test */
    public function it_returns_the_correct_number_of_filters()
    {
        $filters = FilterGroup::if(
            Filter::create()
                ->has('to:me@example.com foo')
                ->labelAndArchive('Test')
        )->otherwise(
            Filter::create()
                ->has('to:me@example.com bar')
                ->read()
        )->otherwise(
            Filter::create()
                ->has('to:me@example.com')
                ->trash()
      );

        $this->assertCount(3, $filters->all());
    }

    /** @test */
    public function same_conditions_are_kept_between_filters()
    {
        $filters = FilterGroup::if(
            Filter::create()
                ->has('to:me@example.com subject:Foo')
                ->read()
        )->otherwise(
            Filter::create()
                ->has('to:me@example.com subject:Bar')
                ->trash()
        );

        $filters->all()->each(function (Filter $filter) {
            $this->assertTrue($filter->getConditions()->contains('to:me@example.com'));
        });
    }
}
