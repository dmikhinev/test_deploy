<?php

namespace Tools\Protocol;

interface ProtocolInterface
{
    /**
     * @param string $str
     * @return mixed
     */
    public function execute($str);
}
