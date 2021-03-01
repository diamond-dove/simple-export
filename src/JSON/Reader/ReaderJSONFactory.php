<?php

namespace DiamondDove\SimpleExport\JSON\Reader;

class ReaderJSONFactory
{
    /**
     * Creates a reader by file extension
     *
     * @param string $path The path to the json file.
     * @return ReaderInterface
     */
    public static function createFromFile(string $path)
    {
        $extension = \strtolower(\pathinfo($path, PATHINFO_EXTENSION));
    }
}
