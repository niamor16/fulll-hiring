<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Vehicle;

final class Vehicle
{
    private string $plate;
    private ?Location $location = null;

    public function __construct(string $plate)
    {
        $this->plate = $plate;
    }

    public function getPlate(): string
    {
        return $this->plate;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(Location $location): self
    {
        if ($this->location && $this->location->equals($location)) {
            throw new \Exception('This location is the already known location');
        }

        $this->location = $location;

        return $this;
    }
}