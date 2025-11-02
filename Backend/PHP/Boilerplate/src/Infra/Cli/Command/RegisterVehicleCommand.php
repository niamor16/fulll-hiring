<?php

namespace Fulll\Infra\Cli\Command;

use Fulll\App\Command\Fleet\RegisterVehicle;
use Fulll\App\Handler\Fleet\RegisterVehicleHandler;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Shared\UniqId;
use Fulll\Infra\Persistence\InMemory\InMemoryFleetRepository;
use Fulll\Infra\Persistence\InMemory\InMemoryVehicleRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'register-vehicle',
)]
class RegisterVehicleCommand extends Command
{
    private RegisterVehicleHandler $handler;

    public function __construct()
    {
        parent::__construct();
        $fleetRepository = new InMemoryFleetRepository();
        $vehicleRepository = new InMemoryVehicleRepository();
        $this->handler = new RegisterVehicleHandler($fleetRepository, $vehicleRepository);
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fleetId', InputArgument::REQUIRED)
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fleetId = $input->getArgument('fleetId');
            if (!$fleetId) {
                throw new \InvalidArgumentException('fleetId is required');
            }

            $vehiclePlateNumber = $input->getArgument('vehiclePlateNumber');
            $fleetUid = UniqId::fromString($fleetId);

            $command = new RegisterVehicle($fleetUid, $vehiclePlateNumber);
            $this->handler->__invoke($command);
            
            $output->writeln("<info>Vehicle registered</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}