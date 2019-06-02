<?php

namespace Reddok\IpStoreBundle\Channel;

use Reddok\IpStoreBundle\Entity\Ip;

interface ChannelInterface
{
    public function invalid(string $address): string;
    public function notFound(string $address): string;
    public function saved(string $address): string;
    public function show(Ip $ip): string;
}