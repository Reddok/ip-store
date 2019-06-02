<?php

namespace IpStoreBundle\Channel\CliChannel;

use IpStoreBundle\Channel\ChannelInterface;
use IpStoreBundle\Entity\Ip;

class CliChannel implements ChannelInterface
{
    public function invalid(string $address): string
    {
        return 'Sorry, but ip ' . $address . ' is invalid. You can provide only iPv4 and iPv6 formats.';
    }

    public function notFound(string $address): string
    {
        return 'There is no ip ' .  $address . ' in store.';
    }

    public function saved(string $address): string
    {
        return 'Ip ' . $address . ' successfully saved.';
    }

    public function show(Ip $ip): string
    {
        return 'Ip ' . $ip->getAddress() . ' stored ' . $ip->getTimesSaved() . ' times.';
    }
}