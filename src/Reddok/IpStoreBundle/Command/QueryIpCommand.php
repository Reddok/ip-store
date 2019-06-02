<?php

namespace Reddok\IpStoreBundle\Command;

use Reddok\IpStoreBundle\Channel\CliChannel\CliChannel;
use Reddok\IpStoreBundle\IpStoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class QueryIpCommand extends Command
{
    private $store;
    protected static $defaultName = 'ipstore:query';

    public function __construct(IpStoreManager $store, CliChannel $channel)
    {
        $this->store = $store;
        $this->store->setChannel($channel);
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Return how many times this ip was added')
            ->setHelp('You must provide ip as argument. You will receive how many times
                this ip was added including this addition or error message if ip is invalid. Ip must be provided in iPv4 or iPv6 formats.')
            ->addArgument('ip', InputArgument::REQUIRED, 'Ip from which you want get information');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $address = $input->getArgument('ip');
        return $output->writeLn($this->store->query($address));
    }
}