<?php

namespace Tools\Protocol;

class Ssh implements ProtocolInterface
{
    private $host;
    private $port;
    private $user;
    private $password;
    private $keyFilename;
    /**
     * @var resource
     */
    private $connection;

    public function __construct($host, $port = 22, $user, $password = null, $keyFilename = '~/.ssh/id_rsa')
    {
        $this->host = $host;
        $this->port = $port;
        $this->user = $user;
        $this->password = $password;
        $this->keyFilename = $keyFilename;
    }

    /**
     * @param string $str
     * @return resource
     * @throws ProtocolException
     */
    public function execute($str)
    {
        $this->connect();

        if (false === $result = ssh2_exec($this->connection, $str)) {
            throw new ProtocolException(sprintf('An error occurred while executing command "%s"', $str));
        }

        return $result;
    }

    /**
     * @throws AuthException
     */
    private function connect(): void
    {
        if (null !== $this->connection) {
            return;
        }
        if (!$this->connection = ssh2_connect($this->host, $this->port, ['hostkey'=>'ssh-rsa'])) {
            throw new AuthException(sprintf('Can not connect to "%s" port %d.', $this->host, $this->port));
        }

        if (null !== $this->password) {
            if (!ssh2_auth_password($this->connection, $this->user, $this->password)) {
                throw new AuthException(sprintf('Can not connect to "%s:%d" with username "%s" and password "***"', $this->host, $this->port, $this->user));
            }
        } else {
            if (!ssh2_auth_pubkey_file($this->connection, $this->user, $this->keyFilename.'.pub', $this->keyFilename)) {
                throw new AuthException(sprintf('Can not connect to "%s:%d" with username "%s" and key "%s"', $this->host, $this->port, $this->user, $this->keyFilename.'.pub'));
            }
        }
    }
}
