<?php

namespace Config\Parser;

class Php implements ParserInterface
{
    public function getName(): string
    {
        return 'php';
    }

    /**
     * {@inheritdoc}
     */
    public function parse($filename): array
    {
        ob_start();
        $result = require $filename;
        ob_clean();

        if (!is_array($result)) {
            throw new NotParsableValueException('PHP config should return an array.');
        }

        return $result;
    }
}
