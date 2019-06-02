<?php

namespace IpStoreBundle\Storage;

use IpStoreBundle\Entity\Ip;

interface StorageInterface
{
    public function query(string $address): Ip;
    public function add(string $address): void;
}