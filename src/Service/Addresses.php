<?php

namespace Opdavies\GmailFilterBuilder\Service;

/**
 * A service for loading addresses from separate files.
 */
class Addresses
{
    /**
     * Load addresses from a file.
     *
     * @param string $filename The filename to load.
     *
     * @return array
     */
    public static function load($filename = 'my-addresses.php')
    {
        $file = (new static())->getDirectoryPaths()
            ->map(function ($path) use ($filename) {
                return $path . $filename;
            })
            ->first(function ($file) {
                return file_exists($file);
            });

        if ($file) {
            return include $file;
        }

        return [];
    }

    /**
     * Get the potential directory names containing the addresses file.
     *
     * @return \Illuminate\Support\Collection
     */
    protected function getDirectoryPaths()
    {
        return collect([
            getenv('HOME') . DIRECTORY_SEPARATOR . '.gmail-filters' . DIRECTORY_SEPARATOR,
            getenv('HOME') . DIRECTORY_SEPARATOR . '.config/gmail-filters' . DIRECTORY_SEPARATOR,
        ]);
    }
}
