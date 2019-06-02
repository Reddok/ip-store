<?php

namespace Reddok\IpStoreBundle\Storage;

use Reddok\IpStoreBundle\Entity\Ip;

interface StorageInterface
{
    public function query(string $address): Ip;
    public function add(string $address): void;
}