<?php

namespace Fulll\Infra\Persistence\Sql;

use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Shared\UniqId;

class SqlFleetRepository implements FleetRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result === false ? null : $result;
    }

    public function findById(UniqId $fleetId): ?Fleet
    {
        $sql = 'SELECT id, user_id FROM fleets WHERE id = ?';
        $result = $this->findOne($sql, [$fleetId]);
        if (!$result) {
            return null;
        }
        $fleet = Fleet::create(UniqId::fromString($result['id']), UniqId::fromString($result['user_id']));

        $platesStatement = $this->pdo->prepare('SELECT plate FROM fleet_vehicles WHERE fleet_id = ?');
        $platesStatement->execute([$fleetId]);
        $plates = $platesStatement->fetchAll(\PDO::FETCH_ASSOC);
        if ($plates) {
            foreach ($plates as $plate) {
                $fleet->registerVehiclePlate($plate['plate']);
            }
        }
        return $fleet;
    }

    public function findByUserId(UniqId $userId): ?Fleet
    {
        $sql = 'SELECT id, user_id FROM fleets WHERE user_id = ?';
        $result = $this->findOne($sql, [$userId]);
        if (!$result) {
            return null;
        }
        $fleet = Fleet::create(UniqId::fromString($result['id']), UniqId::fromString($result['user_id']));

        $platesStatement = $this->pdo->prepare('SELECT plate FROM fleet_vehicles WHERE fleet_id = ?');
        $platesStatement->execute([$fleet->getId()]);
        $plates = $platesStatement->fetchAll();
        if ($plates) {
            foreach ($plates as $plate) {
                $fleet->registerVehiclePlate($plate['plate']);
            }
        }
        return $fleet;
    }

    public function save(Fleet $fleet): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->prepare('INSERT INTO fleets (id, user_id) VALUES (:id, :user_id) ON CONFLICT (id) DO UPDATE SET user_id = EXCLUDED.user_id')
                ->execute([
                    'id' => $fleet->getId(),
                    'user_id' => $fleet->getUserId(),
                ]);

            $this->pdo
                ->prepare('DELETE FROM fleet_vehicles WHERE fleet_id = ?')
                ->execute([$fleet->getId()]);

            foreach ($fleet->getVehiclePlates() as $plate) {
                $this->pdo
                    ->prepare('INSERT INTO fleet_vehicles (fleet_id, plate) VALUES (:fleet_id, :plate)')
                    ->execute([
                        'fleet_id' => $fleet->getId(),
                        'plate' => $plate
                    ]);
            }

            $this->pdo->commit();
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    public function reset(): void
    {
        $this->pdo->beginTransaction();
        try {
            $this->pdo->exec('TRUNCATE TABLE fleets CASCADE');
            $this->pdo->commit();
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}