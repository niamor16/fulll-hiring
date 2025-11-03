<?php
declare(strict_types=1);

namespace Fulll\App\Handler\Fleet;

use Fulll\App\Command\Fleet\RegisterVehicle;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Vehicle\Vehicle;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;

final class RegisterVehicleHandler
{
    private FleetRepositoryInterface $fleetRepository;
    private VehicleRepositoryInterface $vehicleRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository, VehicleRepositoryInterface $vehicleRepository)
    {
        $this->fleetRepository = $fleetRepository;
        $this->vehicleRepository = $vehicleRepository;
    }

    public function __invoke(RegisterVehicle $command)
    {
        $fleet = $this->fleetRepository->findById($command->getFleetId());
        if (!$fleet) {
            throw new \Exception('No fleed found with id ' . $command->getFleetId());
        }

        if (!$this->vehicleRepository->findByPlate($command->getVehiclePlate())) {
            $vehicle = Vehicle::create($command->getVehiclePlate());
            $this->vehicleRepository->save($vehicle);
        }

        $fleet->registerVehiclePlate($command->getVehiclePlate());
        $this->fleetRepository->save($fleet);
    }
}