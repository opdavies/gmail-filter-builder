<?php

require_once __DIR__ . '/vendor/autoload.php';

use function Opdavies\GmailFilterBuilder\filter;

return [
    filter()
        ->from('foo@example.com')
        ->labelAndArchive('Example'),

    filter()
        ->from('bar@example.com')
        ->important(),
];
