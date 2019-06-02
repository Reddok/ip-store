<?php

namespace IpStoreBundle\Channel\CliChannel\Command;

use IpStoreBundle\Entity\Ip;
use IpStoreBundle\Exception\IpInvalidException;
use IpStoreBundle\Storage\StorageInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AddIpCommand extends Command
{
    private $storage;
    private $validator;

    public function __construct(StorageInterface $storage, ValidatorInterface $validator)
    {
        $this->storage = $storage;
        $this->validator = $validator;
        parent::__construct('ipstore:add-ip');
    }

    protected function configure()
    {
        $this->setDescription('Adds ip to store')
            ->setHelp('This command allow you to add ip to our store. You must provide desired ip as argument. You will receive how many times
                this ip was added including this addition or error message if ip is invalid. Ip must be provided in iPv4 or iPv6 formats.')
            ->addArgument('ip', InputArgument::REQUIRED, 'Ip for storing');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $address = $input->getArgument('ip');
        $ip = new Ip($address);

        try {
            $possibleErrors = $this->validator->validate($ip);

            if(count($possibleErrors)) {
                throw new IpInvalidException();
            }

            $this->storage->add($ip);

            $ip = $this->storage->query($ip);

            $output->writeLn('Ip ' . $ip->getAddress() . ' successfully saved.');
            return $output->writeLn('Ip ' . $ip->getAddress() . ' stored ' . $ip->getTimesSaved() . ' times.');
        } catch(IpInvalidException $exception) {
            return $output->writeLn('Sorry, but ip ' . $ip->getAddress() . ' is invalid. You can provide only iPv4 and iPv6 formats.');
        }
    }
}