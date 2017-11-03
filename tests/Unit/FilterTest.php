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
            $this->filter->has('something')
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
            $this->filter->from('foo@example.com')
        );

        // Ensure that we can set multiple from addresses.
        $this->assertEquals(
            ['from' => ['foo@example.com', 'bar@example.com']],
            $this->filter->from('foo@example.com', 'bar@example.com')
        );
    }

    /**
     * @covers Filter::label()
     */
    public function testLabel()
    {
        $this->assertEquals(['label' => 'Foo'], $this->filter->label('Foo'));
    }

    /**
     * @covers Filter::archive()
     */
    public function testArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true'],
            $this->filter->archive()
        );
    }

    /**
     * @covers Filter::labelAndArchive()
     */
    public function testLabelAndArchive()
    {
        $this->assertEquals(
            ['shouldArchive' => 'true', 'label' => 'Foo'],
            $this->filter->labelAndArchive('Foo')
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
            $this->filter->spam()
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
            $this->filter->neverSpam()
        );
    }

    /**
     * @covers Filter::trash()
     */
    public function testTrash()
    {
        $this->assertEquals(
            ['shouldTrash' => 'true'],
            $this->filter->trash()
        );
    }
}
