<?php

declare(strict_types=1);

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Fulll\App\Calculator;
use Fulll\App\Command\Fleet\CreateUserFleet;
use Fulll\App\Command\Fleet\RegisterVehicle;
use Fulll\App\Handler\Fleet\CreateUserFleetHandler;
use Fulll\App\Handler\Fleet\RegisterVehicleHandler;
use Fulll\App\Handler\Vehicle\ParkVehicleHandler;
use Fulll\Domain\Model\Fleet\Fleet;
use Fulll\Domain\Model\Fleet\FleetRepositoryInterface;
use Fulll\Domain\Model\Shared\UniqId;
use Fulll\Domain\Model\Vehicle\Location;
use Fulll\Domain\Model\Vehicle\Vehicle;
use Fulll\Domain\Model\Vehicle\VehicleRepositoryInterface;
use Fulll\Infra\Persistence\InMemory\InMemoryFleetRepository;
use Fulll\Infra\Persistence\InMemory\InMemoryVehicleRepository;
use Fulll\Infra\Persistence\Sql\SqlFleetRepository;
use Fulll\Infra\Persistence\Sql\SqlVehicleRepository;

#[\AllowDynamicProperties]
class FeatureContext implements Context
{
    private FleetRepositoryInterface $fleetRepository;
    private VehicleRepositoryInterface $vehicleRepository;
    private RegisterVehicleHandler $registerVehicleHandler;
    private ParkVehicleHandler $parkVehicleHandler;
    private CreateUserFleetHandler $createUserFleetHandler;

    private UniqId $myFleetId;
    private UniqId $myUserId;
    private UniqId $otherUserId;
    private UniqId $otherFleetId;
    private string $myVehiclePlate = 'AA-123-CD';
    private ?Vehicle $myVehicle = null;
    private ?string $lastError = null;
    private ?Location $myLocation = null;

    public function __construct()
    {
        $this->myFleetId = UniqId::fromString('11111111');
        $this->otherFleetId = UniqId::fromString('2222222222');
        $this->myUserId = UniqId::fromString('33333333');
        $this->otherUserId = UniqId::fromString('4444444444');

//        $this->fleetRepository = new InMemoryFleetRepository();
//        $this->vehicleRepository = new InMemoryVehicleRepository();
        $pdo = new PDO('pgsql:host=backend-db;dbname=fleet;user=user;password=pwd');
        $this->fleetRepository = new sqlFleetRepository($pdo);
        $this->vehicleRepository = new sqlVehicleRepository($pdo);

        $this->registerVehicleHandler = new RegisterVehicleHandler($this->fleetRepository, $this->vehicleRepository);
        $this->parkVehicleHandler = new ParkVehicleHandler($this->fleetRepository, $this->vehicleRepository);
        $this->createUserFleetHandler = new CreateUserFleetHandler($this->fleetRepository);
    }

    /**
     * @BeforeScenario
     */
    public function resetDb(BeforeScenarioScope $scope): void
    {
        $this->fleetRepository->reset();
        $this->vehicleRepository->reset();
    }

    //    ==============================================================================

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
        $this->iCreateMyFleet();
    }

    /**
     * @Given a vehicle
     */
    public function aVehicle(): void
    {
        if (!$this->myVehicle) {
            $vehicle = Vehicle::create($this->myVehiclePlate);
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
        if (is_null($this->myLocation)) {
            $this->myLocation = Location::create(45.78310313723511, 4.80946412181288);
        }
    }

    /**
     * @When /^I park my vehicle at this location$/
     */
    public function iParkMyVehicleAtThisLocation()
    {
        $command = new \Fulll\App\Command\Vehicle\ParkVehicule($this->myFleetId, $this->myVehiclePlate, $this->myLocation);
        try {
            $this->parkVehicleHandler->__invoke($command);
        } catch (\Exception $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    /**
     * @Then /^the known location of my vehicle should verify this location$/
     */
    public function theKnownLocationOfMyVehicleShouldVerifyThisLocation()
    {
        $vehicule = $this->vehicleRepository->findByPlate($this->myVehiclePlate);
        if (!$vehicule) {
            throw new \RuntimeException('vehicule not found');
        }

        $location = $vehicule->getLocation();
        if (!$location || !$this->myLocation->equals($location)) {
            throw new \RuntimeException(sprintf('%s is expected, got %s', $this->myLocation, $location));
        }
    }

//    ===============================================

    /**
     * @Given /^my vehicle has been parked into this location$/
     */
    public function myVehicleHasBeenParkedIntoThisLocation()
    {
        $this->iParkMyVehicleAtThisLocation();
    }

    /**
     * @When /^I try to park my vehicle at this location$/
     */
    public function iTryToParkMyVehicleAtThisLocation()
    {
        $this->iParkMyVehicleAtThisLocation();
    }

    /**
     * @Then /^I should be informed that my vehicle is already parked at this location$/
     */
    public function iShouldBeInformedThatMyVehicleIsAlreadyParkedAtThisLocation()
    {
        if ($this->lastError !== 'This location is the already known location') {
            throw new \RuntimeException(sprintf('I should be informed that my vehicle is already parked at this location : %s', $this->lastError));
        }
    }

//    ===============================================

    /**
     * @Given /^my user id$/
     */
    public function myUserId()
    {
        if (!$this->myUserId) {
            $this->myUserId = UniqId::new();
        }
    }

    /**
     * @Then /^my fleet should be created$/
     */
    public function myFleetShouldBeCreated()
    {
        if (!$this->fleetRepository->findById($this->myFleetId)) {
            throw new \RuntimeException('this fleet does not exist');
        }
    }

    /**
     * @When /^I have already created my fleet$/
     */
    public function iHaveAlreadyCreatedMyFleet()
    {
        try {
            $this->iCreateMyFleet();
        } catch (\Exception $exception) {
            $this->lastError = $exception->getMessage();
        }
    }

    /**
     * @Then /^I should be informed that I already have a fleet$/
     */
    public function iShouldBeInformedThatIAlreadyHaveAFleet()
    {
        if ($this->lastError !== 'A fleet already exists for this user') {
            throw new \RuntimeException(sprintf('I should be informed that I already have a fleet : %s', $this->lastError));
        }
    }

    /**
     * @When /^I create my fleet$/
     */
    public function iCreateMyFleet()
    {
        $command = new CreateUserFleet($this->myUserId, $this->myFleetId);
        $this->createUserFleetHandler->__invoke($command);
    }
}
