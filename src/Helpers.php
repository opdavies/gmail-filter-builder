<?php

declare(strict_types=1);

namespace Opdavies\GmailFilterBuilder;

use Opdavies\GmailFilterBuilder\Model\Filter;

function filter(): Filter
{
    return new Filter();
}
