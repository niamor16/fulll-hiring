<?php

namespace Fulll\Domain\Model\Vehicle;

final class Location
{
    private float $latitude;
    private float $longitude;
    private float $altitude;

    public function __construct(float $latitude, float $longitude, float $altitude = 0)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->altitude = $altitude;
    }

    public function __toString(): string
    {
        return $this->latitude . ', ' . $this->longitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getAltitude(): float
    {
        return $this->altitude;
    }
}