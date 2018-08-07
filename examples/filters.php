<?php

use Opdavies\GmailFilterBuilder\Filter;

require_once __DIR__ . '/vendor/autoload.php';

return [
    Filter::create()
        ->from('foo@example.com')
        ->labelAndArchive('Example'),

    Filter::create()
        ->from('bar@example.com')
        ->important(),
];
