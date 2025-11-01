<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Fleet;

interface FleetRepositoryInterface
{
    public function findById(int $fleetId): ?Fleet;

    public function save(Fleet $fleet): bool;
}