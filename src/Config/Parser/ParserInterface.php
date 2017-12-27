<?php

namespace Config\Parser;

interface ParserInterface
{
    /**
     * @param string $filename
     * @return array
     * @throws NotParsableValueException
     */
    public function parse($filename): array;

    /**
     * @return string
     */
    public function getName(): string;
}
