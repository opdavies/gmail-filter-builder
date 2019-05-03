<?php

namespace Opdavies\Tests\GmailFilterBuilder\Model;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Opdavies\GmailFilterBuilder\Model\FilterAction;
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
        $this->assertSame('hasTheWord', $condition->getPropertyName());
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
        $this->assertSame('doesNotHaveTheWord', $condition->getPropertyName());
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
        $this->assertSame('from', $condition->getPropertyName());
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
        $this->assertSame('from', $condition->getPropertyName());
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
        $this->assertSame('to', $condition->getPropertyName());
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
        $this->assertSame('to', $condition->getPropertyName());
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
        $this->assertSame('subject', $condition->getPropertyName());
        $this->assertSame('"foo@example.com"', $condition->getValues()->first());
    }

    /** @test */
    public function can_filter_based_on_multiple_subjects()
    {
        $filter = $this->filter->subject(['foo', 'bar']);

        $condition = $filter->getConditions()->first();

        $this->assertInstanceOf(FilterCondition::class, $condition);
        $this->assertSame('subject', $condition->getPropertyName());
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
        $this->assertSame('hasAttachment', $condition->getPropertyName());
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
        $this->assertSame('hasTheWord', $condition->getPropertyName());
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
        $this->assertSame('excludeChats', $condition->getPropertyName());
        $this->assertSame('true', $condition->getValues()->first());
    }

    /**
     * @test
     * @covers ::label
     */
    public function labels_can_be_added()
    {
        $filter = $this->filter->label('Foo');

        /** @var FilterAction $action */
        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('label', $action->getPropertyName());
        $this->assertSame('Foo', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::archive
     */
    public function messages_can_be_archived()
    {
        $filter = $this->filter->archive();

        /** @var FilterAction $action */
        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('shouldArchive', $action->getPropertyName());
        $this->assertSame('true', $action->getvalues()->first());
    }

    /**
     * @test
     * @covers ::labelAndArchive
     */
    public function messages_can_be_labelled_and_archived()
    {
        $filter = $this->filter->labelAndArchive('test');

        $actions = $filter->getActions();
        $this->assertCount(2, $actions);

        tap($actions->first(), function (FilterAction $action) {
            $this->assertSame('label', $action->getPropertyName());
            $this->assertSame('test', $action->getValues()->first());
        });

        tap($actions->last(), function (FilterAction $action) {
            $this->assertSame('shouldArchive', $action->getPropertyName());
            $this->assertSame('true', $action->getValues()->first());
        });
    }

    /**
     * @test
     * @covers ::spam
     */
    public function messages_can_be_marked_as_spam()
    {
        $filter = $this->filter->spam();

        $actions = $filter->getActions();
        $this->assertCount(2, $actions);

        tap($actions->first(), function (FilterAction $action) {
            $this->assertSame('shouldSpam', $action->getPropertyName());
            $this->assertSame('true', $action->getValues()->first());
        });

        tap($actions->last(), function (FilterAction $action) {
            $this->assertSame('shouldNeverSpam', $action->getPropertyName());
            $this->assertSame('false', $action->getValues()->first());
        });
    }

    /**
     * @test
     * @covers ::neverSpam
     */
    public function messages_can_be_marked_as_not_spam()
    {
        $filter = $this->filter->neverSpam();

        $actions = $filter->getActions();
        $this->assertCount(2, $actions);

        tap($actions->first(), function (FilterAction $action) {
            $this->assertSame('shouldSpam', $action->getPropertyName());
            $this->assertSame('false', $action->getValues()->first());
        });

        tap($actions->last(), function (FilterAction $action) {
            $this->assertSame('shouldNeverSpam', $action->getPropertyName());
            $this->assertSame('true', $action->getValues()->first());
        });
    }

    /**
     * @test
     * @covers ::trash
     */
    public function messages_can_be_deleted()
    {
        $filter = $this->filter->trash();

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('shouldTrash', $action->getPropertyName());
        $this->assertSame('true', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::read
     */
    public function messages_can_be_marked_as_read()
    {
        $filter = $this->filter->read();

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('markAsRead', $action->getPropertyName());
        $this->assertSame('true', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::star
     */
    public function messages_can_be_starred()
    {
        $filter = $this->filter->star();

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('shouldStar', $action->getPropertyName());
        $this->assertSame('true', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::forward
     */
    public function messages_can_be_forwarded()
    {
        $filter = $this->filter->forward('foo@example.com');

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('forwardTo', $action->getPropertyName());
        $this->assertSame('foo@example.com', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::important
     */
    public function messages_can_be_marked_as_important()
    {
        $filter = $this->filter->important();

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('shouldAlwaysMarkAsImportant', $action->getPropertyName());
        $this->assertSame('true', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::notImportant
     */
    public function messages_can_be_marked_as_not_important()
    {
        $filter = $this->filter->notImportant();

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('shouldNeverMarkAsImportant', $action->getPropertyName());
        $this->assertSame('true', $action->getValues()->first());
    }

    /**
     * @test
     * @covers ::categorise
     */
    public function messages_can_be_categorised()
    {
        $filter = $this->filter->categorise('Foo');

        $action = $filter->getActions()->first();

        $this->assertInstanceOf(FilterAction::class, $action);
        $this->assertSame('smartLabelToApply', $action->getPropertyName());
        $this->assertSame('Foo', $action->getValues()->first());
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
            $this->filter->from(['foo@example.com', 'bar@example.com'])
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
