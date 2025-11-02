<?php

namespace Fulll\App\Handler\Fleet;

use Fulll\App\Command\Fleet\CreateUserFleet;
use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;

final class CreateUserFleetHandler
{
    private FleetRepositoryInterface $fleetRepository;

    public function __construct(FleetRepositoryInterface $fleetRepository)
    {
        $this->fleetRepository = $fleetRepository;
    }

    public function __invoke(CreateUserFleet $command): void
    {
        // max 1 fleet by user
        if ($this->fleetRepository->findByUserId($command->getUserId())) {
            throw new \Exception('A fleet already exists for this user');
        }

        $fleet = Fleet::create($command->getFleetId(), $command->getUserId());
        $this->fleetRepository->save($fleet);
    }
}