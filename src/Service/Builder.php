<?php

namespace Opdavies\GmailFilterBuilder\Service;

use Opdavies\GmailFilterBuilder\Filter;
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

    public function __construct(array $filters, $outputFile = 'filters.xml', $writeFile = true) {
        $this->filesystem = new Filesystem();
        $this->filters = $filters;
        $this->outputFile = $outputFile;
        $this->writeFile = $writeFile;

        $this->build();
    }

    public function __toString()
    {
        return $this->build();
    }

    /**
     * Returns the generated XML.
     *
     * @return string
     */
    public function getXml()
    {
        return $this->xml;
    }

    /**
     * Build XML for a set of filters.
     *
     * @return string
     */
    private function build()
    {
        $prefix = "<?xml version='1.0' encoding='UTF-8'?>" . PHP_EOL . "<feed xmlns='http://www.w3.org/2005/Atom' xmlns:apps='http://schemas.google.com/apps/2006'>";
        $suffix = '</feed>';

        $xml = collect($this->filters)->map(function ($items) {
            return $this->buildEntry($items);
        })->implode(PHP_EOL);

        $this->xml = collect([$prefix, $xml, $suffix])->implode(PHP_EOL);

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
    private function buildEntry(Filter $filter)
    {
        $entry = collect($filter->getProperties())->map(function ($value, $key) {
            return $this->buildProperty($value, $key);
        })->implode(PHP_EOL);

        return collect(['<entry>', $entry, '</entry>'])->implode(PHP_EOL);
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
            htmlentities($this->implode($value)),
        ]);
    }

    /**
     * Implode values with the appropriate prefix, suffix and separator.
     */
    private function implode($value, $separator = '|')
    {
        if (is_string($value)) {
            return $value;
        }

        if (is_array($value) && count($value) === 1) {
            return reset($value);
        }

        return sprintf('(%s)', collect($value)->implode($separator));
    }
}
