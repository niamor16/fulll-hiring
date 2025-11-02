<?php

namespace Fulll\Domain\Model\Vehicle;

final class Location
{
    private float $latitude;
    private float $longitude;
    private ?float $altitude;

    private function __construct(float $latitude, float $longitude, ?float $altitude = null)
    {
        $this->latitude = round($latitude, 8);
        $this->longitude = round($longitude, 8);
        $this->altitude = $altitude ? round($altitude, 2) : null;
    }

    static public function create(float $latitude, float $longitude, ?float $altitude = null): self
    {
        return new self($latitude, $longitude, $altitude);
    }

    public function __toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }

    public function equals(Location $location): bool
    {
        return $this->latitude === $location->getLatitude() && $this->longitude === $location->getLongitude();
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getAltitude(): ?float
    {
        return $this->altitude;
    }
}