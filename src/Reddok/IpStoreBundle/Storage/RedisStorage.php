<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle\Storage;

use Reddok\IpStoreBundle\Entity\Ip;
use Reddok\IpStoreBundle\Exception\IpNotFoundException;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

/**
 * Class RedisStorage
 * @package Reddok\IpStoreBundle\Storage
 */
class RedisStorage implements StorageInterface
{
    /**
     * @var ClientInterface
     */
    private $redis;

    /**
     * RedisStorage constructor.
     * @param ClientInterface $redis
     */
    public function __construct(ClientInterface $redis)
    {
        $this->redis = $redis;
    }

    /**
     * @param string $address
     * @return Ip
     * @throws IpNotFoundException
     */
    public function query(string $address): Ip
    {
        $timesSaved = $this->redis->get($address);

        if (!$timesSaved) {
            throw new IpNotFoundException();
        }

        return new Ip($address, (int) $timesSaved);
    }

    /**
     * @param string $address
     */
    public function add(string $address): void
    {
        $timesSaved = (int) $this->redis->get($address) + 1;
        $this->redis->set($address, $timesSaved);
    }
}