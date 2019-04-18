<?php

use Opdavies\GmailFilterBuilder\Model\Filter;

return [
    Filter::create()
        ->from('example.com')
        ->labelAndArchive('Test'),
];
