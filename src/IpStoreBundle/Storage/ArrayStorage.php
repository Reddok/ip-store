<?php

namespace IpStoreBundle\Storage;

use IpStoreBundle\Entity\Ip;
use IpStoreBundle\Exception\IpNotFoundException;

class ArrayStorage implements StorageInterface
{
    private $storage = [];

    public function query(string $address): Ip
    {
        if (!isset($this->storage[$address])) {
            throw new IpNotFoundException();
        }
        $timesSaved = $this->storage[$address];

        return new Ip($address, $timesSaved);
    }

    public function add(string $address): void
    {
        $this->storage[$address] = ($this->storage[$address] ?? 0) + 1;
    }
}