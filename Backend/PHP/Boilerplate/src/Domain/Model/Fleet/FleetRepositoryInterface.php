<?php
declare(strict_types=1);

namespace Fulll\Domain\Model\Fleet;

use Fulll\Domain\Model\Shared\UniqId;

interface FleetRepositoryInterface
{
    public function findById(UniqId $fleetId): ?Fleet;

    public function findByUserId(UniqId $userId): ?Fleet;

    public function save(Fleet $fleet): void;
}