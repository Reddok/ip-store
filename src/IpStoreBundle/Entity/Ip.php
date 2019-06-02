<?php

namespace IpStoreBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Ip
 * @package IpStoreBundle\Entity
 */
class Ip
{
    /**
     * @var string
     */
    private $ip;
    /**
     * @var int
     */
    private $timesSaved;

    public function __construct(string $ip, int $timesSaved = 0)
    {
        $this->ip = $ip;
        $this->timesSaved = $timesSaved;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->ip;
    }

    /**
     * @return int
     */
    public function getTimesSaved(): int
    {
        return $this->timesSaved;
    }

    /**
     * @param int $timesSaved
     */
    public function setTimesSaved(int $timesSaved): void
    {
        $this->timesSaved = $timesSaved;
    }
}