<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle\Tests;

use Reddok\IpStoreBundle\Entity\Ip;
use Reddok\IpStoreBundle\IpStoreManager;
use Reddok\IpStoreBundle\Storage\StorageInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IpStoreManagerTest extends KernelTestCase
{
    private $manager;
    private $mockStorage;

    public function __construct()
    {
        $this->mockStorage = $this->createMock(StorageInterface::class);
        parent::__construct();
    }

    protected function setUp()
    {
        static::bootKernel();
        $validator = static::$kernel->getContainer()->get('testvalidator');
        $this->manager = new IpStoreManager($this->mockStorage, $validator);
    }

    public function testAddAddress()
    {
        $address = '192.168.1.1';
        $this->mockStorage->expects($this->once())
            ->method('add');

        $this->manager->add($address);
    }

    /**
     * @expectedException Reddok\IpStoreBundle\Exception\IpInvalidException
     */
    public function testAddInvalidAddress()
    {
        $address = '192.168.1.257';
        $this->manager->add($address);
    }

    public function testQueryAddress()
    {
        $address = '192.168.1.1';
        $expectedIp = new Ip($address);

        $this->mockStorage->expects($this->once())
            ->method('query')
            ->with($address)
            ->willReturn($expectedIp);

        $actualIp = $this->manager->query($address);

        $this->assertSame($expectedIp, $actualIp);
    }

    /**
     * @expectedException Reddok\IpStoreBundle\Exception\IpInvalidException
     */
    public function testQueryInvalidAddress()
    {
        $address = '192.168.1.257';
        $this->manager->query($address);
    }

    public function testAddIpv6Address()
    {
        $address = '2001:0db8:11a3:09d7:1f34:8a2e:07a0:765d';
        $this->mockStorage->expects($this->once())
            ->method('add')
            ->with($address);

        $this->manager->add($address);
    }
}
