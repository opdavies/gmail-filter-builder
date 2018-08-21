<?php

use Opdavies\GmailFilterBuilder\Model\Filter;

return [
    Filter::create()
        ->from('baz@example.com')
        ->labelAndArchive('Test 3'),
];
