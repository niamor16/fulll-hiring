<?php

namespace Fulll\Infra\Persistence\InMemory;

use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Shared\UniqId;

class InMemoryFleetRepository implements FleetRepositoryInterface
{
    /**
     * @var Fleet[]
     */
    private array $rows = [];

    public function findById(UniqId $fleetId): ?Fleet
    {
        return $this->rows[(string)$fleetId] ?? null;
    }

    public function save(Fleet $fleet): void
    {
        if (!$fleet->getId()) {
            throw new \Exception('Fleet ID not set');
        }

        $this->rows[(string)$fleet->getId()] = $fleet;
    }

    public function findByUserId(UniqId $userId): ?Fleet
    {
        return array_find($this->rows, fn($row) => $row->getUserId()->equals($userId));
    }

    public function reset(): void
    {
        $this->rows = [];
    }
}