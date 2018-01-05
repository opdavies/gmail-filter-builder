<?php

use Opdavies\GmailFilterBuilder\Builder;
use Opdavies\GmailFilterBuilder\Filter;
use PHPUnit\Framework\TestCase;

class BuilderTest extends TestCase
{
    public function testBuild()
    {
        $filterA = (new Filter())
            ->from(['foo@example.com', 'test@example.com'])
            ->label('Some label')
            ->archive();

        $filterB = (new Filter())
            ->has('from:bar@example.com')
            ->star()
            ->important();

        $result = new Builder([$filterA, $filterB]);

        $expected = <<<EOF
<?xml version='1.0' encoding='UTF-8'?>
<feed xmlns='http://www.w3.org/2005/Atom' xmlns:apps='http://schemas.google.com/apps/2006'>
<entry>
<apps:property name='from' value='(foo@example.com|test@example.com)'/>
<apps:property name='label' value='Some label'/>
<apps:property name='shouldArchive' value='true'/>
</entry>
<entry>
<apps:property name='hasTheWord' value='from:bar@example.com'/>
<apps:property name='shouldStar' value='true'/>
<apps:property name='shouldAlwaysMarkAsImportant' value='true'/>
</entry>
</feed>
EOF;

        $this->assertEquals($expected, $result->__toString());
    }
}
