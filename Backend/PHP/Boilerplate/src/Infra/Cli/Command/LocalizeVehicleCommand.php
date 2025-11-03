<?php

namespace Fulll\Infra\Cli\Command;

use Fulll\App\Command\Vehicle\ParkVehicule;
use Fulll\App\Handler\Vehicle\ParkVehicleHandler;
use Fulll\Domain\Model\Shared\UniqId;
use Fulll\Domain\Model\Vehicle\Location;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'localize-vehicle'
)]
class LocalizeVehicleCommand extends Command
{
    private ParkVehicleHandler $handler;

    public function __construct(ParkVehicleHandler $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('fleetId', InputArgument::REQUIRED)
            ->addArgument('vehiclePlateNumber', InputArgument::REQUIRED)
            ->addArgument('lat', InputArgument::REQUIRED)
            ->addArgument('lng', InputArgument::REQUIRED)
            ->addArgument('alt', InputArgument::OPTIONAL);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            $fleetId = $input->getArgument('fleetId');
            if (!$fleetId) {
                throw new \InvalidArgumentException('Fleet id cannot be empty');
            }

            $vehiclePlateNumber = $input->getArgument('vehiclePlateNumber');
            $fleetUid = UniqId::fromString($fleetId);
            $latitude = $input->getArgument('lat');
            $longitude = $input->getArgument('lng');
            $altitude = $input->getArgument('alt') ?? 0;

            $location = Location::create($latitude, $longitude, $altitude);
            $command = new ParkVehicule($fleetUid, $vehiclePlateNumber, $location);
            $this->handler->__invoke($command);
            $output->writeln("<info>Vehicle location saved</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}