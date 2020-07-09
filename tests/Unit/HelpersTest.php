<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use Opdavies\GmailFilterBuilder\Model\Filter as FilterClass;
use function Opdavies\GmailFilterBuilder\filter;

final class HelpersTest extends TestCase
{
    /** @test */
    public function it_returns_a_filter_object(): void
    {
        $this->assertEquals(new FilterClass(), filter());
    }
}
