<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Fleet;

use Fulll\Domain\Model\Shared\UniqId;

final class Fleet
{
    private UniqId $id;
    private UniqId $userId;

    /**
     * @var string[]
     */
    private array $vehiclePlates = [];

    public function __construct(UniqId $id, UniqId $userId)
    {
        $this->id = $id;
        $this->userId = $userId;
    }

    static public function create(UniqId $id, UniqId $userId): Fleet
    {
        return new self($id, $userId);
    }

    public function hasVehiclePlate(string $vehiclePlate): bool
    {
        return in_array($vehiclePlate, $this->vehiclePlates);
    }

    /**
     * @param string $vehiclePlate
     * @return bool
     * @throws \Exception
     */
    public function registerVehiclePlate(string $vehiclePlate): bool
    {
        if ($this->hasVehiclePlate($vehiclePlate)) {
            throw new \Exception('This vehicle has already been registered into this fleet');
        }
        $this->vehiclePlates[] = $vehiclePlate;
        return true;
    }

    /**
     * @return string[]
     */
    public function getVehiclePlates(): array
    {
        return $this->vehiclePlates;
    }

    public function getId(): UniqId
    {
        return $this->id;
    }

    public function getUserId(): UniqId
    {
        return $this->userId;
    }
}