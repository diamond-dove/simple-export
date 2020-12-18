<?php

namespace DiamondDove\SimpleExport\JSON\Reader;

use DiamondDove\SimpleExport\Common\Exception\IOException;
use SebastianBergmann\FileIterator\Iterator;

/**
 * Interface ReaderInterface
 */
interface ReaderInterface
{
    /**
     * Prepares the reader to read the given file. It also makes sure
     * that the file exists and is readable.
     *
     * @param  string $filePath Path of the file to be read
     * @throws IOException
     */
    public function open(string $filePath): void;

    /**
     * Returns an iterator to iterate over sheets.
     *
     * @throws Exception\ReaderNotOpenedException If called before opening the reader
     */
    public function getJSONIterator(): Iterator;

    /**
     * Closes the reader, preventing any additional reading
     *
     */
    public function close(): void;
}
