<?php

namespace Event\Handler\Traits;

use Tools\Protocol\ProtocolInterface;

trait RemoteConsole
{
    protected $console;

    public function setRemoteConsole(ProtocolInterface $console)
    {
        $this->console = $console;
    }

    public function getConsole()
    {
        return $this->console;
    }
}
