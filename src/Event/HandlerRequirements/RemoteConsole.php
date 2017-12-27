<?php

namespace Event\HandlerRequirements;

use Tools\Protocol\ProtocolInterface;

interface RemoteConsole
{
    public function setRemoteConsole(ProtocolInterface $protocol);
}
