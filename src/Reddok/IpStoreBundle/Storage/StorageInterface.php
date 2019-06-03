<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle\Storage;

use Reddok\IpStoreBundle\Entity\Ip;

/**
 * Interface StorageInterface
 * @package Reddok\IpStoreBundle\Storage
 */
interface StorageInterface
{
    /**
     * @param string $address
     * @return Ip
     */
    public function query(string $address): Ip;

    /**
     * @param string $address
     */
    public function add(string $address): void;
}