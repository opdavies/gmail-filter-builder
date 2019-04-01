<?php

namespace Tests\Opdavies\GmailFilterBuilder;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Model\FilterGroup;
use PHPUnit\Framework\TestCase;

class OtherwiseTest extends TestCase
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

        $this->assertCount(3, $filters->toArray());
    }
}
