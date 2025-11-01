<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Vehicle;

final class Vehicle
{
    private string $plate;

    public function __construct(string $plate)
    {
        $this->plate = $plate;
    }

    public function getPlate(): string
    {
        return $this->plate;
    }
}