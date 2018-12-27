<?php

namespace Opdavies\GmailFilterBuilder\Service;

use Tightenco\Collect\Support\Collection;

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
    public static function load(string $filename = 'my-addresses.php'): array
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
     * @return \Tightenco\Collect\Support\Collection
     */
    protected function getDirectoryPaths(): Collection
    {
        return collect([
            getenv('HOME') . DIRECTORY_SEPARATOR . '.gmail-filters' . DIRECTORY_SEPARATOR,
            getenv('HOME') . DIRECTORY_SEPARATOR . '.config/gmail-filters' . DIRECTORY_SEPARATOR,
        ]);
    }
}
