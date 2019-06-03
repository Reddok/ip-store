<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle;

use Reddok\IpStoreBundle\Entity\Ip;
use Reddok\IpStoreBundle\Exception\IpInvalidException;
use Reddok\IpStoreBundle\Storage\StorageInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class IpStoreManager
 * @package Reddok\IpStoreBundle
 */
class IpStoreManager extends Bundle
{
    /**
     * @var StorageInterface
     */
    private $storage;
    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * IpStoreManager constructor.
     * @param StorageInterface $storage
     * @param ValidatorInterface $validator
     */
    public function __construct(StorageInterface $storage, ValidatorInterface $validator)
    {
        $this->storage = $storage;
        $this->validator = $validator;
    }

    /**
     * @param string $address
     * @throws IpInvalidException
     */
    public function add(string $address): void
    {
        $this->validate($address);
        $this->storage->add($address);
    }

    /**
     * @param string $address
     * @return Ip
     * @throws IpInvalidException
     */
    public function query(string $address): Ip
    {
        $this->validate($address);
        return $this->storage->query($address);
    }

    /**
     * @param string $address
     * @throws IpInvalidException
     */
    protected function validate(string $address)
    {
        $ipConstraint = new Assert\Ip(['version' => Assert\Ip::ALL]);

        $possibleErrors = $this->validator->validate($address, $ipConstraint);

        if(count($possibleErrors)) {
            throw new IpInvalidException();
        }
    }
}
