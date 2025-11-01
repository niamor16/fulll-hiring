<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Fleet;

final class Fleet
{
    private int $id;
    private ?int $userId = null;

    /**
     * @var string[]
     */
    private array $vehiclePlates = [];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    static public function create(int $id, int $userId): Fleet
    {
        $fleet = new self($id);
        $fleet->userId = $userId;
        return $fleet;
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

    public function getId(): int
    {
        return $this->id;
    }
}