<?php

class GmailFilterTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var GmailFilter
     */
    private $filter;

    /**
     * {@inheritdoc}
     */
    protected function setUp() {
        parent::setUp();

        $this->filter = new GmailFilter();
    }

    public function testSingleFrom() {
        // TODO: Does this need to be done each time?
        $output = $this->createBuilder([
            $this->filter->from(['foo@example.com'])
        ]);

        $this->assertContains('<apps:property name=\'from\' value=\'foo@example.com\'/>', $output);
    }

    public function testMultipleFrom() {
        $output = $this->createBuilder([
            $this->filter->from(['foo@example.com', 'bar@example.com'])
        ]);

        $this->assertContains('<apps:property name=\'from\' value=\'foo@example.com OR bar@example.com\'/>', $output);
    }

    /**
     * @param GmailFilter[] $filters An array of filters.
     *
     * @return string A string representation of GmailFilterBuilder.
     */
    private function createBuilder($filters) {
        return (string) new GmailFilterBuilder($filters);
    }

}
