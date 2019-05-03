<?php

namespace Opdavies\GmailFilterBuilder\Model;

use Tightenco\Collect\Support\Collection;

abstract class FilterProperty
{
    /** @var string  */
    private $property;

    /** @var Collection */
    private $values;

    public function __construct(string $property, $values)
    {
        $this->property = $property;
        $this->values = collect($values);
    }

    public function getPropertyName(): string
    {
        return $this->property;
    }

    public function getValues(): Collection
    {
        return $this->values;
    }
}
