<?php

namespace Fulll\Infra\Persistence\InMemory;

use Fulll\Domain\Model\Vehicle\Vehicle;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;

class InMemoryVehicleRepository implements VehicleRepositoryInterface
{
    private array $rows = [];

    public function findByPlate(string $plate): ?Vehicle
    {
        return $this->rows[$plate] ?? null;
    }

    public function save(Vehicle $vehicle): bool
    {
        if (!$vehicle->getPlate()) {
            return false;
        }

        $this->rows[$vehicle->getPlate()] = $vehicle;
        return true;
    }
}