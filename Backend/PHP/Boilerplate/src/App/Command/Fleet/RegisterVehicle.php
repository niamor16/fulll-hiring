<?php
declare(strict_types=1);

namespace Fulll\App\Command\Fleet;

use Fulll\Domain\Model\Shared\UniqId;

final class RegisterVehicle
{
    private UniqId $fleetId;
    private string $vehiclePlate;

    public function __construct(UniqId $fleetId, string $vehiclePlate)
    {
        $this->fleetId = $fleetId;
        $this->vehiclePlate = $vehiclePlate;
    }

    public function getVehiclePlate(): string
    {
        return $this->vehiclePlate;
    }

    public function getFleetId(): UniqId
    {
        return $this->fleetId;
    }
}