<?php

namespace Fulll\App\Handler\Vehicle;

use Fulll\App\Command\Vehicle\ParkVehicule;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;

final class ParkVehicleHandler
{
    private FleetRepositoryInterface $fleetRepository;
    private VehicleRepositoryInterface $vehicleRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository, VehicleRepositoryInterface $vehicleRepository)
    {
        $this->fleetRepository = $fleetRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function __invoke(ParkVehicule $command)
    {
        $fleet = $this->fleetRepository->findById($command->getFleetId());
        if (!$fleet) {
            throw new \Exception('No fleet found with id ' . $command->getFleetId());
        }

        if(!$fleet->hasVehiclePlate($command->getVehiclePlate())) {
            throw new \Exception(sprintf('No vehicle with plate %s registered in the fleet', $command->getVehiclePlate()));
        }

        $vehicle = $this->vehicleRepository->findByPlate($command->getVehiclePlate());
        if (!$vehicle) {
            throw new \Exception('No vehicle found with plate ' . $command->getVehiclePlate());
        }

        $vehicle->setLocation($command->getLocation());
        $this->vehicleRepository->save($vehicle);
    }
}