<?php

namespace Opdavies\GmailFilterBuilder\Service;

/**
 * Load and combine partials from multiple files.
 */
class Partials
{
    /**
     * Load partials from a directory.
     *
     * @param string $directoryName The name of the directory containing the partials.
     *
     * @return array The loaded filters.
     */
    public static function load($directoryName = 'filters')
    {
        $files = (new static())->getFilePattern($directoryName);

        return collect(glob($files))
            ->map(function ($filename) {
                return include $filename;
            })
            ->flatten(1)
            ->all();
    }

    /**
     * Build the glob pattern to load partials from the directory.
     *
     * Assumes it is always relative to the current directory, and that the
     * filters are always in files with a .php extension.
     *
     * @param string $directoryName The name of the directory.
     *
     * @return string The full path.
     */
    protected function getFilePattern(string $directoryName)
    {
        return getcwd() . DIRECTORY_SEPARATOR . $directoryName . DIRECTORY_SEPARATOR . '*.php';
    }
}
