<?php

namespace DiamondDove\SimpleExport;

use DiamondDove\SimpleExport\JSON\Reader\ReaderInterface;
use DiamondDove\SimpleExport\JSON\Reader\ReaderJSONFactory;

class JSONReader
{
    private ReaderInterface $reader;

    public static function create(string $file): self
    {
        return new static($file);
    }

    public function __construct(
        private string $path
    ) {
        $this->reader = ReaderJSONFactory::createFromFile($path);
    }
}
