<?php

namespace Fulll\App\Command\Fleet;

use Fulll\Domain\Model\Shared\UniqId;

final class CreateUserFleet
{
    private UniqId $userId;
    private UniqId $fleetId;

    public function __construct(UniqId $userId, UniqId $fleetId)
    {
        $this->userId = $userId;
        $this->fleetId = $fleetId;
    }

    public function getUserId(): UniqId
    {
        return $this->userId;
    }

    public function getFleetId(): UniqId
    {
        return $this->fleetId;
    }
}