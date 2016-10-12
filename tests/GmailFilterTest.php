<?php

class GmailFilterTest extends \PHPUnit_Framework_TestCase
{
    public function testSingleFrom() {
        $filters[] = GmailFilter::create()
            ->from(['foo@example.com']);

        // TODO: Does this need to be done each time?
        $builder = (string) new GmailFilterBuilder($filters);

        $this->assertContains('<apps:property name=\'from\' value=\'foo@example.com\'/>', $builder);
    }

    public function testMultipleFrom() {
        $filters[] = GmailFilter::create()
            ->from(['foo@example.com', 'bar@example.com']);

        // TODO: Does this need to be done each time?
        $builder = (string) new GmailFilterBuilder($filters);

        $this->assertContains('<apps:property name=\'from\' value=\'foo@example.com OR bar@example.com\'/>', $builder);
    }
}
