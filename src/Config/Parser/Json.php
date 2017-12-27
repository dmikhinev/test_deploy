<?php

namespace Config\Parser;

class Json implements ParserInterface
{
    public function getName(): string
    {
        return 'json';
    }

    /**
     * {@inheritdoc}
     */
    public function parse($filename): array
    {
        if (false === $config = file_get_contents($filename)) {
            throw new \InvalidArgumentException(sprintf('Can not read file "%s".', $filename));
        }
        $result = json_decode($config, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new NotParsableValueException(json_last_error_msg());
        }

        return $result;
    }
}
