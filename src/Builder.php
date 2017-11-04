<?php

namespace Opdavies\GmailFilterBuilder;

class Builder
{
    private $filters = [];

    public function __construct(array $filters) {
        $this->filters = $filters;
    }

    public function build()
    {
    }
}
