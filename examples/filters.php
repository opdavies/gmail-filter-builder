<?php

use Opdavies\GmailFilterBuilder\Model\Filter;

require_once __DIR__ . '/../vendor/autoload.php';

return [
    Filter::create()
        ->from('papercall.io')
        ->subject('New Submission For Drupal Camp Bristol 2019')
        ->labelAndArchive('DrupalCamp Bristol'),

    ($filter = Filter::create())
        ->from('papercall.io')
        ->negate($filter->subject('New Submission For Drupal Camp Bristol 2019'))
        ->labelAndArchive('Deletable/Other notifications'),
];
