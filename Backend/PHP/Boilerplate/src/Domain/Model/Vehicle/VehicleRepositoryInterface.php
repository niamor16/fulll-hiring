<?php

namespace Fulll\Domain\Model\Vehicle;

interface VehicleRepositoryInterface
{
    public function findByPlate(string $plate): ?Vehicle;
    public function save(Vehicle $vehicle): void;
}