<?php
declare(strict_types=1);

namespace Fulll\App\Command\Fleet;

final class RegisterVehicle
{
    private int $fleetId;
    private string $vehiclePlate;

    public function __construct(int $fleetId, string $vehiclePlate)
    {
        $this->fleetId = $fleetId;
        $this->vehiclePlate = $vehiclePlate;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }

    public function getFleetId(): int
    {
        return $this->fleetId;
    }
}