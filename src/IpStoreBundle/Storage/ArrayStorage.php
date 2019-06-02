<?php

namespace IpStoreBundle\Storage;

use IpStoreBundle\Entity\Ip;
use IpStoreBundle\Exception\IpNotFoundException;

class ArrayStorage implements StorageInterface
{
    private $storage = [];

    public function query(Ip $ip): Ip
    {
        if (!isset($this->storage[$ip->getAddress()])) {
            throw new IpNotFoundException();
        }
        $ip->setTimesSaved($this->storage[$ip->getAddress()]);

        return $ip;
    }

    public function add(Ip $ip): void
    {
        $this->storage[$ip->getAddress()] = ($this->storage[$ip->getAddress()] ?? 0) + 1;
    }
}