<?php

namespace IpStoreBundle\Storage;

use IpStoreBundle\Entity\Ip;

interface StorageInterface
{
    public function query(Ip $address): Ip;
    public function add(Ip $address): void;
}