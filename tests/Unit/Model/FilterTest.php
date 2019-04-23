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
     * @test
     * @covers::has
     */
    public function can_filter_on_a_has_value()
    {
        $this->assertEquals(
            ['hasTheWord' => 'something'],
            $this->filter->has('something')->toArray()
        );
    }

    /**
     * @test
     * @covers ::hasNot
     */
    public function can_filter_on_a_has_not_value()
    {
        $this->assertEquals(
            ['doesNotHaveTheWord' => 'something'],
            $this->filter->hasNot('something')->toArray()
        );
    }

    /**
     * @test
     * @covers ::from
     */
    public function can_filter_based_on_the_sender()
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
     * @test
     * @covers ::from
     */
    public function no_from_property_exists_if_the_value_is_empty()
    {
        $this->assertArrayNotHasKey('from', $this->filter->from('')->toArray());
        $this->assertArrayNotHasKey('from', $this->filter->from([])->toArray());
    }

    /**
     * @test
     * @covers ::to
     */
    public function can_filter_based_on_the_recipient()
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

    /** @test */
    public function no_to_property_exists_if_the_value_is_empty()
    {
        $this->assertArrayNotHasKey('to', $this->filter->to('')->toArray());
        $this->assertArrayNotHasKey('to', $this->filter->to([])->toArray());
    }

    /**
     * @test
     * @covers ::subject
     */
    public function can_filter_based_on_the_subject()
    {
        $this->assertEquals(
            ['subject' => '"Something"'],
            $this->filter->subject('Something')->toArray()
        );

        $this->assertEquals(
            ['subject' => '"Test"|"Foo bar"'],
            $this->filter->subject(['Test', 'Foo bar'])->toArray()
        );
    }

    /**
     * @test
     * @covers ::hasAttachment
     */
    public function can_filter_based_on_whether_there_is_an_attachment()
    {
        $this->assertEquals(
            ['hasAttachment' => 'true'],
            $this->filter->hasAttachment()->toArray()
        );
    }

    /**
     * @test
     * @covers ::fromList
     */
    public function can_filter_based_on_wether_it_was_sent_to_a_list()
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
     * @test
     * @covers ::excludeChats
     */
    public function chats_can_be_excluded()
    {
        $this->assertEquals(
            ['excludeChats' => 'true'],
            $this->filter->excludeChats()->toArray()
        );
    }

    /**
     * @test
     * @covers ::label
     */
    public function labels_can_be_added()
    {
        $this->assertEquals(
            ['label' => 'Foo'],
            $this->filter->label('Foo')->toArray()
        );
    }

    /**
     * @test
     * @covers ::archive
     */
    public function messages_can_be_archived()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true'],
            $this->filter->archive()->toArray()
        );
    }

    /**
     * @test
     * @covers ::labelAndArchive
     */
    public function messages_can_be_labelled_and_archived()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true', 'label' => 'Foo'],
            $this->filter->labelAndArchive('Foo')->toArray()
        );
    }

    /**
     * @test
     * @covers ::spam
     */
    public function messages_can_be_marked_as_spam()
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
     * @test
     * @covers ::neverSpam
     */
    public function messages_can_be_marked_as_not_spam()
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
     * @test
     * @covers ::trash
     */
    public function messages_can_be_deleted()
    {
        $this->assertEquals(
            ['shouldTrash' => 'true'],
            $this->filter->trash()->toArray()
        );
    }

    /**
     * @test
     * @covers ::read
     */
    public function messages_can_be_marked_as_read()
    {
        $this->assertEquals(
            ['markAsRead' => 'true'],
            $this->filter->read()->toArray()
        );
    }

    /**
     * @test
     * @covers ::star
     */
    public function messages_can_be_starred()
    {
        $this->assertEquals(
            ['shouldStar' => 'true'],
            $this->filter->star()->toArray()
        );
    }

    /**
     * @test
     * @covers ::forward
     */
    public function messages_can_be_forwarded()
    {
        $this->assertEquals(
            ['forwardTo' => 'foo@example.com'],
            $this->filter->forward('foo@example.com')->toArray()
        );
    }

    /**
     * @test
     * @covers ::important
     */
    public function messages_can_be_marked_as_important()
    {
        $this->assertEquals(
            ['shouldAlwaysMarkAsImportant' => 'true'],
            $this->filter->important()->toArray()
        );
    }

    /**
     * @test
     * @covers ::notImportant
     */
    public function messages_can_be_marked_as_not_important()
    {
        $this->assertEquals(
            ['shouldNeverMarkAsImportant' => 'true'],
            $this->filter->notImportant()->toArray()
        );
    }

    /**
     * @test
     * @covers ::categorise
     */
    public function messages_can_be_categorised()
    {
        $this->assertEquals(
            ['smartLabelToApply' => 'Foo'],
            $this->filter->categorise('Foo')->toArray()
        );
    }

    /** @test */
    public function methods_can_be_chained()
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

    /** @test */
    public function conditions_can_be_negated()
    {
        $this->filter
            ->from('example.com')
            ->negate($this->filter->subject('test'));

        $this->assertEquals(
            [
                'from' => 'example.com',
                'subject' => '-(test)',
            ],
            $this->filter->toArray()
        );
    }
}
