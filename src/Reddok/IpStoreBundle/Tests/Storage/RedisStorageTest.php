<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle\Tests\Storage;

use PHPUnit\Framework\TestCase;
use Reddok\IpStoreBundle\Entity\Ip;
use Reddok\IpStoreBundle\Storage\RedisStorage;
use SymfonyBundles\RedisBundle\Redis\ClientInterface;

class RedisStorageTest extends TestCase
{
    private $storage;
    private $mockRedis;

    public function __construct()
    {
        $this->mockRedis = $this->createMock(ClientInterface::class);
        $this->storage = new RedisStorage($this->mockRedis);
        parent::__construct();
    }

    public function testAdd()
    {
        $key = '192.168.1.1';

        $this->mockRedis->expects($this->at(1))
            ->method('__call')
            ->with('set', [$key, 1]);

        $this->storage->add($key);
    }

    public function testQuery()
    {
        $key = '192.168.1.1';
        $timesSaved = 2;

        $this->mockRedis->expects($this->once())
            ->method('__call')
            ->with('get', [$key])
            ->willReturn($timesSaved);

        $ip = $this->storage->query($key);

        $this->assertInstanceOf(Ip::class, $ip);
        $this->assertEquals($key, $ip->getAddress());
        $this->assertEquals($timesSaved, $ip->getTimesSaved());
    }

    /**
     * @expectedException Reddok\IpStoreBundle\Exception\IpNotFoundException
     */
    public function testQueryNonExistent()
    {
        $key = '192.168.1.257';

        $this->mockRedis->expects($this->once())
            ->method('__call')
            ->with('get', [$key])
            ->willReturn(null);

        $this->storage->query($key);
    }
}
