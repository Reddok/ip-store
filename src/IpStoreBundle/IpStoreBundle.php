<?php

namespace IpStoreBundle;

use IpStoreBundle\Channel\ChannelInterface;
use IpStoreBundle\Exception\IpNotFoundException;
use IpStoreBundle\Storage\StorageInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

class IpStoreBundle extends Bundle
{
    private $storage;
    private $channel;
    private $validator;

    public function __construct(StorageInterface $storage, ValidatorInterface $validator)
    {
        $this->storage = $storage;
        $this->validator = $validator;
    }

    public function setChannel(ChannelInterface $channel): void
    {
        $this->channel = $channel;
    }

    public function add(string $address): string
    {
        if ($error = $this->validate($address))
        {
            return $error;
        }

        $this->storage->add($address);

        $response = $this->channel->saved($address);
        return $response . $this->query($address);
    }

    public function query(string $address): string
    {
        if ($error = $this->validate($address))
        {
            return $error;
        }

        try {
            $ip = $this->storage->query($address);
            $response = $this->channel->show($ip);
        } catch (IpNotFoundException $exception) {
            $response = $this->channel->notFound($address);
        }

        return $response;
    }

    protected function validate(string $address): ?string
    {
        $ipConstraint = new Assert\Ip();

        $possibleErrors = $this->validator->validate($address, $ipConstraint);

        if(count($possibleErrors)) {
            return $this->channel->invalid($address);
        }

        return null;
    }
}
