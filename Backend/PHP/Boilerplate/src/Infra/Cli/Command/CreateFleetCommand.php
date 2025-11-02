<?php

namespace Fulll\Infra\Cli\Command;

use Fulll\App\Command\Fleet\CreateUserFleet;
use Fulll\App\Handler\Fleet\CreateUserFleetHandler;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Shared\UniqId;
use Fulll\Infra\Persistence\InMemory\InMemoryFleetRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'create',
)]
class CreateFleetCommand extends Command
{
    private CreateUserFleetHandler $handler;
    private FleetRepositoryInterface $fleetRepository;

    public function __construct()
    {
        parent::__construct();
        $this->fleetRepository = new InMemoryFleetRepository();
        $this->handler = new CreateUserFleetHandler($this->fleetRepository);
    }

    protected function configure(): void
    {
        $this->addArgument('userId', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('userId');
        if (!$userId) {
            $output->writeln('<error>UserId is required</error>');
            return Command::FAILURE;
        }

        try {
            $fleetId = UniqId::new();
            $userUid = UniqId::fromString($userId);
            $command = new CreateUserFleet($userUid, $fleetId);
            $this->handler->__invoke($command);
            $output->writeln("<info>Fleet created : $fleetId</info>");
            return Command::SUCCESS;
        } catch (\Exception $exception) {
            $output->writeln("<error>{$exception->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}