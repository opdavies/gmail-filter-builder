<?php

use Opdavies\GmailFilterBuilder\Model\Filter;

return [
    Filter::create()
        ->from('foo@example.com')
        ->labelAndArchive('Test'),

    Filter::create()
        ->from('bar@example.com')
        ->labelAndArchive('Test 2'),
];
