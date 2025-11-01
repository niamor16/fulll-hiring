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
            throw new \Exception('No fleed found with id ' . $command->getFleetId());
        }

        $vehicle = $this->vehicleRepository->findByPlate($command->getVehiclePlate());
        if (!$vehicle) {
            throw new \Exception('No vehicle found with plate ' . $command->getVehiclePlate());
        }

        $vehicle->setLocation($command->getLocation());
        $saved = $this->vehicleRepository->save($vehicle);
        if (!$saved) {
            throw new \Exception('Failed to save fleet');
        }
    }
}