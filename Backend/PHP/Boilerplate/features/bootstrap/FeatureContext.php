<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Fulll\App\Calculator;
use Fulll\App\Command\Fleet\RegisterVehicle;
use Fulll\App\Handler\Fleet\RegisterVehicleHandler;
use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Vehicle\Vehicle;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;
use Fulll\Infra\Persistence\InMemory\InMemoryFleetRepository;
use Fulll\Infra\Persistence\InMemory\InMemoryVehicleRepository;

#[\AllowDynamicProperties]
class FeatureContext implements Context
{
    private FleetRepositoryInterface $fleetRepository;
    private VehicleRepositoryInterface $vehicleRepository;
    private RegisterVehicleHandler $registerVehicleHandler;

    private int $myUserId = 1;
    private int $myFleetId = 1;
    private int $otherUserId = 2;
    private int $otherFleetId = 2;
    private string $myVehiclePlate = 'AA-123-CD';
    private ?Vehicle $myVehicle = null;
    private ?string $lastError = null;

    public function __construct()
    {
        $this->fleetRepository = new InMemoryFleetRepository();
        $this->vehicleRepository = new InMemoryVehicleRepository();
        $this->registerVehicleHandler = new RegisterVehicleHandler($this->fleetRepository, $this->vehicleRepository);
    }

    /**
     * @When I multiply :a by :b into :var
     */
    public function iMultiply(int $a, int $b, string $var): void
    {
        $calculator = new Calculator();
        $this->$var = $calculator->multiply($a, $b);
    }

    /**
     * @Then :var should be equal to :value
     */
    public function aShouldBeEqualTo(string $var, int $value): void
    {
        if ($value !== $this->$var) {
            throw new \RuntimeException(sprintf('%s is expected to be equal to %s, got %s', $var, $value, $this->$var));
        }
    }

//    ==============================================================================

    /**
     * @Given my fleet
     */
    public function myFleet(): void
    {
        $fleet = Fleet::create($this->myFleetId, $this->myUserId);
        $this->fleetRepository->save($fleet);
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle(): void
    {
        if (!$this->myVehicle) {
            $vehicle = new Vehicle($this->myVehiclePlate);
            $this->vehicleRepository->save($vehicle);
            $this->myVehicle = $vehicle;
        }
    }

    /**
     * @When I register this vehicle into my fleet
     */
    public function iRegisterThisVehicleIntoMyFleet(): void
    {
        $command = new RegisterVehicle($this->myFleetId, $this->myVehiclePlate);
        try {
            $this->registerVehicleHandler->__invoke($command);
        } catch (\Exception $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    /**
     * @Then this vehicle should be part of my vehicle fleet
     */
    public function thisVehicleShouldBePartOfMyVehicleFleet(): void
    {
        $fleet = $this->fleetRepository->findById($this->myFleetId);
        if (!$fleet->hasVehiclePlate($this->myVehiclePlate)) {
            throw new \RuntimeException(sprintf('%s vehicle should be part of my vehicle fleet', $this->myVehiclePlate));
        }
    }

//    ==============================================================

    /**
     * @Given I have registered this vehicle into my fleet
     */
    public function iHaveRegisteredThisVehicleIntoMyFleet(): void
    {
        $this->iRegisterThisVehicleIntoMyFleet();
    }

    /**
     * @When I try to register this vehicle into my fleet
     */
    public function iTryToRegisterThisVehicleIntoMyFleet(): void
    {
        $this->iRegisterThisVehicleIntoMyFleet();
    }

    /**
     * @Then I should be informed this vehicle has already been registered into my fleet
     */
    public function iShouldBeInformedVehicleAlreadyRegistered(): void
    {
        if ($this->lastError !== 'This vehicle has already been registered into this fleet') {
            throw new \RuntimeException(sprintf('I should be informed this this vehicle has already been registered into my fleet : %s', $this->lastError));
        }
    }

//    ===============================================

    /**
     * @Given the fleet of another user
     */
    public function theFleetOfAnotherUser(): void
    {
        $fleet = Fleet::create($this->otherFleetId, $this->otherUserId);
        $this->fleetRepository->save($fleet);
    }

    /**
     * @Given this vehicle has been registered into the other user's fleet
     */
    public function thisVehicleHasBeenRegisteredIntoTheOtherUsersFleet(): void
    {
        $command = new RegisterVehicle($this->otherFleetId, $this->myVehiclePlate);
        $this->registerVehicleHandler->__invoke($command);
    }

//    ===============================================

    /**
     * @Given /^a location$/
     */
    public function aLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @When /^I park my vehicle at this location$/
     */
    public function iParkMyVehicleAtThisLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Then /^the known location of my vehicle should verify this location$/
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

//    ===============================================

    /**
     * @Given /^my vehicle has been parked into this location$/
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @When /^I try to park my vehicle at this location$/
     */
    public function iTryToParkMyVehicleAtThisLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }

    /**
     * @Then /^I should be informed that my vehicle is already parked at this location$/
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        throw new \Behat\Behat\Tester\Exception\PendingException();
    }
}
