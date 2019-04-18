<?php

namespace Opdavies\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Model\Filter;
use Symfony\Component\Filesystem\Filesystem;

class Builder
{
    private $filesystem;

    /**
     * @var array
     */
    private $filters = [];

    /**
     * @var
     */
    private $outputFile;

    /**
     * @var bool
     */
    private $writeFile;

    /**
     * @var string
     */
    private $xml;

    /** @var bool  */
    private $expanded;

    public function __construct(array $filters, $outputFile = 'filters.xml', $writeFile = true, $expanded = false)
    {
        $this->filesystem = new Filesystem();
        $this->filters = $filters;
        $this->outputFile = $outputFile;
        $this->writeFile = $writeFile;
        $this->expanded = $expanded;

        $this->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }

    /**
     * Returns the generated XML.
     *
     * @return string
     */
    public function getXml(): string
    {
        return $this->xml;
    }

    /**
     * Build XML for a set of filters.
     *
     * @return string
     */
    private function build(): void
    {
        $prefix = "<?xml version='1.0' encoding='UTF-8'?>" . $this->glue() . "<feed xmlns='http://www.w3.org/2005/Atom' xmlns:apps='http://schemas.google.com/apps/2006'>";
        $suffix = '</feed>';

        $xml = collect($this->filters)->map(function ($items) {
            return $this->buildEntry($items);
        })->implode($this->glue());

        $this->xml = collect([$prefix, $xml, $suffix])->implode($this->glue());

        if ($this->writeFile) {
            $this->filesystem->dumpFile($this->outputFile, $this->xml);
        }
    }

    /**
     * Build XML for an filter.
     *
     * @param Filter $filter
     *
     * @return string
     */
    private function buildEntry(Filter $filter): string
    {
        $entry = collect($filter->toArray())
            ->map(function ($value, $key): string {
                return $this->buildProperty($value, $key);
            })
            ->implode($this->glue());

        return collect(['<entry>', $entry, '</entry>'])->implode($this->glue());
    }

    /**
     * Build XML for a property.
     *
     * @param string $value
     * @param string $key
     *
     * @return string
     */
    private function buildProperty($value, $key): string
    {
        if (collect(['from', 'to'])->contains($key)) {
            $value = $this->implode($value);
        }

        return vsprintf("<apps:property name='%s' value='%s'/>", [
            $key,
            htmlentities($this->implode($value)),
        ]);
    }

    /**
     * Implode values with the appropriate prefix, suffix and separator.
     */
    private function implode($value, $separator = '|'): string
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value) && count($value) === 1) {
            return reset($value);
        }

        return sprintf('(%s)', collect($value)->implode($separator));
    }

    private function glue()
    {
        return $this->expanded ? PHP_EOL : null;
    }
}
