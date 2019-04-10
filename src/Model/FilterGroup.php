<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Spatie\CollectionMacros\Macros\EachCons;
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

    /**
     * Get all filters within this filter group.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->filters;
    }

    public function getConditions(): Collection
    {
        return $this->filters->map->getConditions();
    }

    public function getUpdatedConditions(): Collection
    {
        Collection::macro('eachCons', ((new EachCons())->__invoke()));

        $conditions = clone $this->getConditions();

        return $conditions->eachCons(2)->map(function ($filter) {
            list($previous, $current) = $filter;

            return $previous->zip($current)->map(function (Collection $a): array {
//                dump(['a' => $a]);

                if ($a[1] === null) {
                    return [$a[0]];
                }

                if ($a[0] == $a[1]) {
                    return [$a[0]];
                }
                return [$a[0], "!{$a[1]}"];
            })->flatten(1);
        });
    }


}
