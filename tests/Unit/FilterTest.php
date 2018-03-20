<?php

namespace Opdavies\Tests\GmailFilterBuilder;

use Opdavies\GmailFilterBuilder\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
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
     * @covers Filter::hasNot()
     */
    public function testHasNot()
    {
        $this->assertEquals(
            ['doesNotHaveTheWord' => 'something'],
            $this->filter->hasNot('something')->getProperties()
        );
    }

    /**
     * @covers Filter::from()
     */
    public function testFrom()
    {
        // Ensure that we can set one from address.
        $this->assertEquals(
            ['from' => ['foo@example.com']],
            $this->filter->from('foo@example.com')->getProperties()
        );

        // Ensure that we can set multiple from addresses.
        $this->assertEquals(
            ['from' => ['foo@example.com', 'bar@example.com']],
            $this->filter->from(['foo@example.com', 'bar@example.com'])->getProperties()
        );
    }

    /**
     * @covers Filter::to()
     */
    public function testTo()
    {
        $this->assertEquals(
            ['to' => ['foo@example.com']],
            $this->filter->to('foo@example.com')->getProperties()
        );

        $this->assertEquals(
            ['to' => ['bar@example.com', 'baz@example.com']],
            $this->filter->to(['bar@example.com', 'baz@example.com'])->getProperties()
        );
    }

    /**
     * @covers Filter::subject()
     */
    public function testSubject()
    {
        $this->assertEquals(
            ['subject' => 'Something'],
            $this->filter->subject('Something')->getProperties()
        );
    }

    /**
     * @covers Filter::hasAttachment()
     */
    public function testHasAttachment()
    {
        $this->assertEquals(
            ['hasAttachment' => 'true'],
            $this->filter->hasAttachment()->getProperties()
        );
    }

    public function testExcludeChats()
    {
        $this->assertEquals(
            ['excludeChats' => 'true'],
            $this->filter->excludeChats()->getProperties()
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
                'from' => ['foo@example.com', 'bar@example.com'],
                'hasTheWord' => 'Something',
                'excludeChats' => 'true',
                'label' => 'Foo',
                'markAsRead' => 'true',
                'shouldArchive' => 'true',
                'shouldNeverSpam' => 'true',
                'shouldSpam' => 'false',
                'shouldStar' => 'true',
                'shouldAlwaysMarkAsImportant' => 'true',
            ],
            $this->filter->from(['foo@example.com ', 'bar@example.com'])
                ->has('Something')
                ->excludeChats()
                ->labelAndArchive('Foo')
                ->read()
                ->important()
                ->neverSpam()
                ->star()
                ->getProperties()
        );
    }
}
