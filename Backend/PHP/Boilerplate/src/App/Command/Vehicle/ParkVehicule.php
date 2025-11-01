<?php

namespace Fulll\App\Command\Vehicle;

use Fulll\Domain\Model\Vehicle\Location;

final class ParkVehicule
{
    private int $fleetId;
    private string $vehiclePlate;
    private Location $location;

    public function __construct(int $fleetId, string $vehiclePlate, Location $location)
    {
        $this->fleetId = $fleetId;
        $this->vehiclePlate = $vehiclePlate;
        $this->location = $location;
    }

    public function getFleetId(): int
    {
        return $this->fleetId;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }

    public function getLocation(): Location
    {
        return $this->location;
    }
}