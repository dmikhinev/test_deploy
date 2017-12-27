<?php

namespace Config;

use Config\Parser\NotParsableValueException;
use Config\Parser\ParserInterface;

class Processor
{
    /**
     * @var ParserInterface[]
     */
    private $parsers = [];

    /**
     * @param ParserInterface $parser
     * @return Processor
     */
    public function addParser(ParserInterface $parser)
    {
        if (array_key_exists($parser->getName(), $this->parsers)) {
            throw new \InvalidArgumentException(sprintf('Parser for format "%s" already registered as "%s".', $parser->getName(), get_class($this->parsers[$parser->getName()])));
        }
        $this->parsers[$parser->getName()] = $parser;

        return $this;
    }

    /**
     * @param string[] $configs
     * @return Config
     */
    public function process(array $configs)
    {
        $result = new Config();

        foreach ($configs as $filename) {
            $result = $this->processConfig($this->parseConfig($filename), $result);
        }

        return $result;
    }

    private function processConfig(array $config, Config $result)
    {
        if (isset($config['events'])) {
            foreach ($config['events'] as $k => $event) {
                $result->addEvent($k, $event);
            }
            unset($config['events']);
        }
        if (isset($config['events_order'])) {
            $result->setEventsOrder($config['events_order']);
            unset($config['events_order']);
        }
        if (isset($config['protocol'])) {
            $result->setProtocol($config['protocol']);
            unset($config['protocol']);
        }
        foreach ($config as $k => $v) {
            $result->setParameter($k, $v);
        }

        return $result;
    }

    /**
     * @param string $filename
     * @return array
     * @throws NotParsableValueException
     */
    protected function parseConfig(string $filename): array
    {
        foreach ($this->parsers as $parser) {
            try {
                $config = $parser->parse($filename);

                return $config;
            } catch (NotParsableValueException $e) {
            }
        }
        throw new NotParsableValueException('Config can not be parsed.');
    }
}
