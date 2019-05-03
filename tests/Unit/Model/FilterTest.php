<?php

namespace Opdavies\Tests\GmailFilterBuilder\Model;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Model\FilterCondition;
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
        $filter = $this->filter->has('something');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('hasTheWord', $condition->getProperty());
        $this->assertSame('something', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::hasNot
     */
    public function can_filter_on_a_has_not_value()
    {
        $filter = $this->filter->hasNot('something else');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('doesNotHaveTheWord', $condition->getProperty());
        $this->assertSame('something else', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::from
     */
    public function can_filter_based_on_a_single_sender()
    {
        $filter = $this->filter->from('foo@example.com');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('from', $condition->getProperty());
        $this->assertSame('foo@example.com', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::from
     */
    public function can_filter_based_on_multiple_senders()
    {
        $addresses = ['foo@example.com', 'bar@example.com'];

        $filter = $this->filter->from($addresses);

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('from', $condition->getProperty());
        $this->assertSame($addresses, $condition->getValues()->toArray());
    }

    /**
     * @test
     * @covers ::to
     */
    public function can_filter_based_on_a_single_recipient()
    {
        $filter = $this->filter->to('foo@example.com');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('to', $condition->getProperty());
        $this->assertSame('foo@example.com', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::from
     */
    public function can_filter_based_on_multiple_recipients()
    {
        $addresses = ['foo@example.com', 'bar@example.com'];

        $filter = $this->filter->to($addresses);

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('to', $condition->getProperty());
        $this->assertSame($addresses, $condition->getValues()->toArray());
    }

    /**
     * @test
     * @covers ::subject
     */
    public function can_filter_based_on_a_single_subject()
    {
        $filter = $this->filter->subject('foo@example.com');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('subject', $condition->getProperty());
        $this->assertSame('"foo@example.com"', $condition->getValues()->first());
    }

    /** @test */
    public function can_filter_based_on_multiple_subjects()
    {
        $filter = $this->filter->subject(['foo', 'bar']);

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('subject', $condition->getProperty());
        $this->assertSame(['"foo"', '"bar"'], $condition->getValues()->toArray());
    }

    /**
     * @test
     * @covers ::hasAttachment
     */
    public function can_filter_based_on_whether_there_is_an_attachment()
    {
        $filter = $this->filter->hasAttachment();

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('hasAttachment', $condition->getProperty());
        $this->assertSame('true', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::fromList
     */
    public function can_filter_based_on_wether_it_was_sent_to_a_list()
    {
        $filter = $this->filter->fromList('php-weekly');

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('hasTheWord', $condition->getProperty());
        $this->assertSame('list:php-weekly', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::excludeChats
     */
    public function chats_can_be_excluded()
    {
        $filter = $this->filter->excludeChats();

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('excludeChats', $condition->getProperty());
        $this->assertSame('true', $condition->getValues()->first());
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
}
