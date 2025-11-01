<?php

namespace Fulll\Infra\Persistence\InMemory;

use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;

class InMemoryFleetRepository implements FleetRepositoryInterface
{
    private array $rows = [];

    public function findById(int $fleetId): ?Fleet
    {
        return $this->rows[$fleetId] ?? null;
    }

    public function save(Fleet $fleet): bool
    {
        if (!$fleet->getId()) {
            return false;
        }

        $this->rows[$fleet->getId()] = $fleet;
        return true;
    }
}