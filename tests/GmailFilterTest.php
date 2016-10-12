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

        $this->assertContains('name=\'from\' value=\'foo@example.com\'', $output);
    }

    public function testMultipleFrom() {
        $output = $this->createBuilder([
            $this->filter->from(['foo@example.com', 'bar@example.com'])
        ]);

        $this->assertContains('name=\'from\' value=\'foo@example.com OR bar@example.com\'', $output);
    }

    public function testSingleTo() {
        // TODO: Does this need to be done each time?
        $output = $this->createBuilder([
            $this->filter->to(['foo@example.com'])
        ]);

        $this->assertContains('name=\'to\' value=\'foo@example.com\'', $output);
    }

    public function testMultipleTo() {
        $output = $this->createBuilder([
            $this->filter->to(['foo@example.com', 'bar@example.com'])
        ]);

        $this->assertContains('name=\'to\' value=\'foo@example.com OR bar@example.com\'', $output);
    }

    public function testArchive()
    {
        $output = $this->createBuilder([
            $this->filter->archive()
        ]);

        $this->assertContains('name=\'shouldArchive\' value=\'true\'', $output);
        $this->assertNotContains('name=\'shouldArchive\' value=\'false\'', $output);
    }

    public function testLabelAndArchive()
    {
        $output = $this->createBuilder([
            $this->filter->labelAndArchive('foo')
        ]);

        $this->assertContains('name=\'label\' value=\'foo\'', $output);

        $this->assertContains('name=\'shouldArchive\' value=\'true\'', $output);
        $this->assertNotContains('name=\'shouldArchive\' value=\'false\'', $output);
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
