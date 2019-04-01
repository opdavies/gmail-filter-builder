<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Tightenco\Collect\Support\Collection;

class FilterGroup
{
    /** @var Collection */
    private $filters;

    public function __construct()
    {
        $this->filters = new Collection();
    }

    public static function if(Filter $filter): self
    {
        $self = new static();

        $self->filters->push($filter);

        return $self;
    }

    public function otherwise(Filter $filter): self
    {
        $this->filters->push($filter);

        return $this;
    }

    public function toArray(): array
    {
        return $this->filters->toArray();
    }
}
