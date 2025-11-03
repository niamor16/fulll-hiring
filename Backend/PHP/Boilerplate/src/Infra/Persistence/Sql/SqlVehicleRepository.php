<?php

namespace Fulll\Infra\Persistence\Sql;

use Fulll\Domain\Model\Vehicle\Location;
use Fulll\Domain\Model\Vehicle\Vehicle;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;

class SqlVehicleRepository implements VehicleRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByPlate(string $plate): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT plate, latitude, longitude, altitude FROM vehicles WHERE plate = ?');
        $stmt->execute([$plate]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$result) {
            return null;
        }

        $vehicle = Vehicle::create($plate);
        if (!empty($result['latitude'])
            && !empty($result['longitude'])) {
            $location = Location::create(($result['latitude']), ($result['longitude']), ($result['altitude'] ?? 0));
            $vehicle->setLocation($location);
        }

        return $vehicle;
    }

    public function save(Vehicle $vehicle): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo
                ->prepare('INSERT INTO vehicles (plate, latitude, longitude, altitude) 
                            VALUES (:plate, :latitude, :longitude, :altitude)
                            ON CONFLICT (plate) DO UPDATE SET latitude = EXCLUDED.latitude, longitude = EXCLUDED.longitude, altitude = EXCLUDED.altitude')
                ->execute([
                    'plate' => $vehicle->getPlate(),
                    'latitude' => $vehicle->getLocation()?->getLatitude(),
                    'longitude' => $vehicle->getLocation()?->getLongitude(),
                    'altitude' => $vehicle->getLocation()?->getAltitude(),
                ]);

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}