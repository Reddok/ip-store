<?php

namespace IpStoreBundle\Storage;

use IpStoreBundle\Entity\Ip;
use IpStoreBundle\Exception\IpNotFoundException;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class RedisStorage implements StorageInterface
{
    private $redis;

    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    public function query(string $address): Ip
    {
        $timesSaved = $this->redis->get($address);

        if (!$timesSaved) {
            throw new IpNotFoundException();
        }

        return new Ip($address, $timesSaved);
    }

    public function add(string $address): void
    {
        $timesSaved = (int) $this->redis->get($address) + 1;
        $this->redis->set($address, $timesSaved);
    }
}