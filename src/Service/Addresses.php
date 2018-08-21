<?php

namespace Opdavies\GmailFilterBuilder\Service;

/**
 * A service for loading addresses from separate files.
 */
class Addresses
{
    /**
     * The name of the default directory containing the address files.
     */
    const DIRECTORY_NAME = '.gmail-filters';

    /**
     * Load addresses from a file.
     *
     * @param string $filename The filename to load.
     *
     * @return array
     */
    public static function load($filename = 'my-addresses.php')
    {
        if (file_exists($file = (new static())->getDirectoryPath() . $filename)) {
            return include $file;
        }

        return [];
    }

    /**
     * Get the directory name containing the addresses file.
     *
     * Defaults to a .gmail-filters directory within the user's home directory.
     *
     * @return string
     */
    protected function getDirectoryPath()
    {
        return getenv('HOME') . DIRECTORY_SEPARATOR . self::DIRECTORY_NAME . DIRECTORY_SEPARATOR;
    }
}
