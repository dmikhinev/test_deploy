<?php

namespace Config;

use Event\HandlerRequirements\RemoteConsole;
use Tools\Protocol\ProtocolException;
use Tools\Protocol\ProtocolInterface;
use Tools\Protocol\Ssh;

class Config implements \Iterator
{
    private $parameters = [];

    /**
     * @var callable[]
     */
    private $events = [];

    /**
     * @var string[]
     */
    private $eventOrder = [];

    /**
     * @var ProtocolInterface
     */
    private $protocol;

    /**
     * @var bool
     */
    private $built = false;

    private $classCache = [];

    /**
     * @param string $key
     * @param mixed $value
     * @return Config
     */
    public function setParameter($key, $value): Config
    {
        if ($this->built) {
            throw new \LogicException('Config aready built.');
        }
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameter($key, $default = null)
    {
        if (array_key_exists($key, $this->parameters)) {
            return $this->parameters[$key];
        }

        return $default;
    }

    /**
     * @param string $name
     * @param callable $event
     * @return Config
     */
    public function addEvent(string $name, $event): Config
    {
        if ($this->built) {
            throw new \LogicException('Config aready built.');
        }
        if (array_key_exists($name, $this->events)) {
            throw new \InvalidArgumentException(sprintf('Event "%s" already registered.', $name));
        }
        $this->events[$name] = $event;

        return $this;
    }

    /**
     * @param array $order
     * @return Config
     */
    public function setEventsOrder(array $order): Config
    {
        if ($this->built) {
            throw new \LogicException('Config aready built.');
        }
        if (empty($this->eventOrder)) {
            $this->eventOrder[] = key($order);
        }
        foreach ($order as $before => $next) {
            if (false === $pos = array_search($before, $this->eventOrder)) {
                throw new \InvalidArgumentException(sprintf('Event order is broken. Event "%s" was not registered.', $before));
            }
            array_splice($this->eventOrder, $pos + 1, 0, $next);
        }

        return $this;
    }

    /**
     * @param ProtocolInterface|array $protocol
     * @return Config
     * @throws ProtocolException
     */
    public function setProtocol($protocol): Config
    {
        if ($this->built) {
            throw new \LogicException('Config aready built.');
        }
        if ($protocol instanceof ProtocolInterface) {
            $this->protocol = $protocol;
        } else {
            if (!isset($protocol['host'])) {
                throw new ProtocolException(sprintf('protocol:host should be defined.'));
            } elseif (!isset($protocol['user'])) {
                throw new ProtocolException(sprintf('protocol:user should be defined.'));
            }
            $this->protocol = new Ssh($protocol['host'], $protocol['port'] ?? 22, $protocol['user'], $protocol['password'] ?? null);
        }

        return $this;
    }

    public function build(): void
    {
        if ($this->built) {
            return;
        }
        foreach ($this->events as &$event) {
            if (is_array($event) && is_string($event[0]) && class_exists($event[0])) {
                $event[0] = $this->buildEvent((string) $event[0]);
            }
        }
        reset($this->events);
        reset($this->eventOrder);

        $this->built = true;
    }

    public function current()
    {
        $this->build();

        return current($this->events);
    }

    public function next()
    {
        $this->build();

        return next($this->events);
    }

    public function key()
    {
        $this->build();

        return key($this->events);
    }

    public function valid()
    {
        $this->build();

        return null !== key($this->events);
    }

    public function rewind()
    {
        $this->build();

        return reset($this->events);
    }

    /**
     * @param string $class
     * @return object
     */
    private function buildEvent(string $class)
    {
        if (array_key_exists($class, $this->classCache)) {
            return $this->classCache[$class];
        }
        $this->classCache[$class] = new $class();

        if ($this->classCache[$class] instanceof RemoteConsole) {
            $this->classCache[$class]->setRemoteConsole($this->protocol);
        }

        return $this->classCache[$class];
    }
}
