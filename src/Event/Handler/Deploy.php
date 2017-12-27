<?php

namespace Event\Handler;

use Event\HandlerRequirements\RemoteConsole;
use Event\Handler\Traits;

class Deploy implements RemoteConsole
{
    use Traits\RemoteConsole;

    public function starting()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function ensureStage()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function setSharedAssets()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function check()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function started()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function updating()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function symlinkShared()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function updated()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function publishing()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function symlinkRelease()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function restart()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function published()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function finishing()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function cleanup()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function finished()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }

    public function logRevision()
    {
        echo __CLASS__.':'.__FUNCTION__.PHP_EOL;
    }
}
