<?php

namespace Opdavies\Tests\GmailFilterBuilder;

use Opdavies\GmailFilterBuilder\Filter;

class FilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();

        $this->filter = new Filter();
    }

    /**
     * @covers Filter::has()
     */
    public function testHas()
    {
        $this->assertEquals(
            ['hasTheWord' => 'something'],
            $this->filter->has('something')->getProperties()
        );
    }

    /**
     * @covers Filter::from()
     */
    public function testFrom()
    {
        // Ensure that we can set one from address.
        $this->assertEquals(
            ['from' => 'foo@example.com'],
            $this->filter->from('foo@example.com')->getProperties()
        );

        // Ensure that we can set multiple from addresses.
        $this->assertEquals(
            ['from' => 'foo@example.com,bar@example.com'],
            $this->filter
                ->from('foo@example.com', 'bar@example.com')
                ->getProperties()
        );
    }

    /**
     * @covers Filter::label()
     */
    public function testLabel()
    {
        $this->assertEquals(
            ['label' => 'Foo'],
            $this->filter->label('Foo')->getProperties()
        );
    }

    /**
     * @covers Filter::archive()
     */
    public function testArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true'],
            $this->filter->archive()->getProperties()
        );
    }

    /**
     * @covers Filter::labelAndArchive()
     */
    public function testLabelAndArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true', 'label' => 'Foo'],
            $this->filter->labelAndArchive('Foo')->getProperties()
        );
    }

    /**
     * @covers Filter::spam()
     */
    public function testSpam()
    {
        $this->assertEquals(
            [
                'shouldSpam' => 'true',
                'shouldNeverSpam' => 'false'
            ],
            $this->filter->spam()->getProperties()
        );
    }

    /**
     * @covers Filter::neverSpam()
     */
    public function testNeverSpam()
    {
        $this->assertEquals(
            [
                'shouldSpam' => 'false',
                'shouldNeverSpam' => 'true'
            ],
            $this->filter->neverSpam()->getProperties()
        );
    }

    /**
     * @covers Filter::trash()
     */
    public function testTrash()
    {
        $this->assertEquals(
            ['shouldTrash' => 'true'],
            $this->filter->trash()->getProperties()
        );
    }

    public function testMarkAsRead()
    {
        $this->assertEquals(
            ['markAsRead' => 'true'],
            $this->filter->read()->getProperties()
        );
    }

    public function testStar()
    {
        $this->assertEquals(
            ['shouldStar' => 'true'],
            $this->filter->star()->getProperties()
        );
    }

    public function testForwardTo()
    {
        $this->assertEquals(
            ['forwardTo' => 'foo@example.com'],
            $this->filter->forward('foo@example.com')->getProperties()
        );
    }

    public function testMarkImportant()
    {
        $this->assertEquals(
            ['shouldAlwaysMarkAsImportant' => 'true'],
            $this->filter->important()->getProperties()
        );
    }

    public function testMarkNotImportant()
    {
        $this->assertEquals(
            ['shouldNeverMarkAsImportant' => 'true'],
            $this->filter->notImportant()->getProperties()
        );
    }
    public function testCategorise()
    {
        $this->assertEquals(
            ['smartLabelToApply' => 'Foo'],
            $this->filter->categorise('Foo')->getProperties()
        );
    }


    public function testMethodsCanBeChained()
    {
        $this->assertEquals(
            [
                'from' => 'foo@example.com,bar@example.com',
                'hasTheWord' => 'Something',
                'label' => 'Foo',
                'markAsRead' => 'true',
                'shouldArchive' => 'true',
                'shouldNeverSpam' => 'true',
                'shouldSpam' => 'false',
                'shouldStar' => 'true',
            ],
            $this->filter->from('foo@example.com ', 'bar@example.com')
                ->has('Something')
                ->labelAndArchive('Foo')
                ->read()
                ->neverSpam()
                ->star()
                ->getProperties()
        );
    }
}
