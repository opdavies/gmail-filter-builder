<?php

namespace Opdavies\GmailFilterBuilder;

class Builder
{
    /**
     * @var array
     */
    private $filters = [];

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function __toString()
    {
        return $this->build();
    }

    /**
     * Build XML for a set of filters.
     *
     * @return string
     */
    private function build()
    {
        $prefix = "<?xml version='1.0' encoding='UTF-8'?><feed xmlns='http://www.w3.org/2005/Atom' xmlns:apps='http://schemas.google.com/apps/2006'>";
        $suffix = '</feed>';

        $xml = collect($this->filters)->map(function ($items) {
            return $this->buildEntry($items);
        })->implode(PHP_EOL);

        return collect([$prefix, $xml, $suffix])->implode(PHP_EOL);
    }

    /**
     * Build XML for an filter.
     *
     * @param Filter $filter
     *
     * @return string
     */
    private function buildEntry(Filter $filter)
    {
        $entry = collect($filter->getProperties())->map(function ($value, $key) {
            return $this->buildProperty($value, $key);
        })->implode('');

        return "<entry>{$entry}</entry>";
    }

    /**
     * Build XML for a property.
     *
     * @param string $value
     * @param string $key
     *
     * @return string
     */
    private function buildProperty($value, $key)
    {
        if (collect(['from', 'to'])->contains($key)) {
            $value = $this->implode($value);
        }

        return vsprintf("<apps:property name='%s' value='%s'/>", [
            $key,
            htmlentities($value),
        ]);
    }

    /**
     * Implode values with the appropriate prefix, suffix and separator.
     */
    private function implode($value, $separator = '|')
    {
        return sprintf('(%s)', collect($value)->implode($separator));
    }
}
