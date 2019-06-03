<?php
declare(strict_types=1);

namespace Reddok\IpStoreBundle\Command;

use Reddok\IpStoreBundle\Exception\IpInvalidException;
use Reddok\IpStoreBundle\Exception\IpNotFoundException;
use Reddok\IpStoreBundle\IpStoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


/**
 * Class QueryIpCommand
 * @package Reddok\IpStoreBundle\Command
 */
class QueryIpCommand extends Command
{
    /**
     * @var IpStoreManager
     */
    private $store;
    /**
     * @var string
     */
    protected static $defaultName = 'ipstore:query';

    /**
     * QueryIpCommand constructor.
     * @param IpStoreManager $store
     */
    public function __construct(IpStoreManager $store)
    {
        $this->store = $store;
        parent::__construct();
    }

    /**
     *
     */
    protected function configure(): void
    {
        $this->setDescription('Return how many times this ip was added')
            ->setHelp('You must provide ip as argument. You will receive how many times
                this ip was added including this addition or error message if ip is invalid. Ip must be provided in iPv4 or iPv6 formats.')
            ->addArgument('ip', InputArgument::REQUIRED, 'Ip from which you want get information');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $address = $input->getArgument('ip');

        try {
            $ip = $this->store->query($address);
            $output->writeLn('Ip ' . $ip->getAddress() . ' stored ' . $ip->getTimesSaved() . ' times.');
        } catch (IpInvalidException $exception) {
            $output->writeLn('Sorry, but ip ' . $address . ' is invalid. You can provide only iPv4 and iPv6 formats.');
        } catch (IpNotFoundException $exception) {
            $output->writeLn('There is no ip ' .  $address . ' in store.');
        }
    }
}