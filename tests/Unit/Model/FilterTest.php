<?php

namespace Opdavies\Tests\GmailFilterBuilder\Model;

use Opdavies\GmailFilterBuilder\Model\Filter;
use PHPUnit\Framework\TestCase;

/**
 * Test creating new filters.
 *
 * @coversDefaultClass \Opdavies\GmailFilterBuilder\Model\Filter
 */
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
     * @covers ::has
     */
    public function testHas()
    {
        $this->assertEquals(
            ['hasTheWord' => 'something'],
            $this->filter->has('something')->toArray()
        );
    }

    /**
     * @covers ::hasNot
     */
    public function testHasNot()
    {
        $this->assertEquals(
            ['doesNotHaveTheWord' => 'something'],
            $this->filter->hasNot('something')->toArray()
        );
    }

    /**
     * @covers ::from
     */
    public function testFrom()
    {
        // Ensure that we can set one from address.
        $this->assertEquals(
            ['from' => ['foo@example.com']],
            $this->filter->from('foo@example.com')->toArray()
        );

        // Ensure that we can set multiple from addresses.
        $this->assertEquals(
            ['from' => ['foo@example.com', 'bar@example.com']],
            $this->filter->from(['foo@example.com', 'bar@example.com'])->toArray()
        );
    }

    /**
     * Test that no 'from' key exists if no values were entered.
     *
     * @covers ::from
     */
    public function testNoFromPropertyExistsIfTheValueIsEmpty()
    {
        $this->assertArrayNotHasKey('from', $this->filter->from('')->toArray());
        $this->assertArrayNotHasKey('from', $this->filter->from([])->toArray());
    }

    /**
     * @covers ::to
     */
    public function testTo()
    {
        $this->assertEquals(
            ['to' => ['foo@example.com']],
            $this->filter->to('foo@example.com')->toArray()
        );

        $this->assertEquals(
            ['to' => ['bar@example.com', 'baz@example.com']],
            $this->filter->to(['bar@example.com', 'baz@example.com'])->toArray()
        );
    }

    /**
     * Test that no 'to' key exists if values were entered.
     */
    public function testNoToPropertyExistsIfTheValueIsEmpty()
    {
        $this->assertArrayNotHasKey('to', $this->filter->to('')->toArray());
        $this->assertArrayNotHasKey('to', $this->filter->to([])->toArray());
    }

    /**
     * @covers ::subject
     */
    public function testSubject()
    {
        $this->assertEquals(
            ['subject' => '"Something"'],
            $this->filter->subject('Something')->toArray()
        );
    }

    /**
     * Test that multiple subject conditions can be added.
     */
    public function testMultipleSubjectsCanBeAdded()
    {
        $this->assertEquals(
            ['subject' => '"Test"|"Foo bar"'],
            $this->filter->subject(['Test', 'Foo bar'])->toArray()
        );
    }

    /**
     * @covers ::hasAttachment
     */
    public function testHasAttachment()
    {
        $this->assertEquals(
            ['hasAttachment' => 'true'],
            $this->filter->hasAttachment()->toArray()
        );
    }

    /**
     * @covers ::fromList
     */
    public function testFromList()
    {
        $this->assertEquals(
            ['hasTheWord' => 'list:foobar'],
            $this->filter->fromList('foobar')->toArray()
        );

        $this->assertEquals(
            ['hasTheWord' => 'list:list-one.com|list-two.com'],
            $this->filter->fromList(['list-one.com', 'list-two.com'])->toArray()
        );
    }

    /**
     * @covers ::excludeChats
     */
    public function testExcludeChats()
    {
        $this->assertEquals(
            ['excludeChats' => 'true'],
            $this->filter->excludeChats()->toArray()
        );
    }

    /**
     * @covers ::label
     */
    public function testLabel()
    {
        $this->assertEquals(
            ['label' => 'Foo'],
            $this->filter->label('Foo')->toArray()
        );
    }

    /**
     * @covers ::archive
     */
    public function testArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true'],
            $this->filter->archive()->toArray()
        );
    }

    /**
     * @covers ::labelAndArchive
     */
    public function testLabelAndArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true', 'label' => 'Foo'],
            $this->filter->labelAndArchive('Foo')->toArray()
        );
    }

    /**
     * @covers ::spam
     */
    public function testSpam()
    {
        $this->assertEquals(
            [
                'shouldSpam' => 'true',
                'shouldNeverSpam' => 'false'
            ],
            $this->filter->spam()->toArray()
        );
    }

    /**
     * @covers ::neverSpam
     */
    public function testNeverSpam()
    {
        $this->assertEquals(
            [
                'shouldSpam' => 'false',
                'shouldNeverSpam' => 'true'
            ],
            $this->filter->neverSpam()->toArray()
        );
    }

    /**
     * @covers ::trash
     */
    public function testTrash()
    {
        $this->assertEquals(
            ['shouldTrash' => 'true'],
            $this->filter->trash()->toArray()
        );
    }

    /**
     * @covers ::read
     */
    public function testMarkAsRead()
    {
        $this->assertEquals(
            ['markAsRead' => 'true'],
            $this->filter->read()->toArray()
        );
    }

    /**
     * @covers ::star
     */
    public function testStar()
    {
        $this->assertEquals(
            ['shouldStar' => 'true'],
            $this->filter->star()->toArray()
        );
    }

    /**
     * @covers ::forward
     */
    public function testForwardTo()
    {
        $this->assertEquals(
            ['forwardTo' => 'foo@example.com'],
            $this->filter->forward('foo@example.com')->toArray()
        );
    }

    /**
     * @covers ::important
     */
    public function testMarkImportant()
    {
        $this->assertEquals(
            ['shouldAlwaysMarkAsImportant' => 'true'],
            $this->filter->important()->toArray()
        );
    }

    /**
     * @covers ::notImportant
     */
    public function testMarkNotImportant()
    {
        $this->assertEquals(
            ['shouldNeverMarkAsImportant' => 'true'],
            $this->filter->notImportant()->toArray()
        );
    }

    /**
     * @covers ::categorise
     */
    public function testCategorise()
    {
        $this->assertEquals(
            ['smartLabelToApply' => 'Foo'],
            $this->filter->categorise('Foo')->toArray()
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
                ->toArray()
        );
    }
}
