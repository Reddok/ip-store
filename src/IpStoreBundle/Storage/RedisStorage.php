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

    public function query(Ip $ip): Ip
    {
        $timesSaved = $this->redis->get($ip->getAddress());

        if (!$timesSaved) {
            throw new IpNotFoundException();
        }
        $ip->setTimesSaved($timesSaved);

        return $ip;
    }

    public function add(Ip $ip): void
    {
        $timesSaved = (int) $this->redis->get($ip->getAddress()) + 1;
        $this->redis->set($ip->getAddress(), $timesSaved);
    }
}