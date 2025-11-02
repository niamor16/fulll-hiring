Feature: Create a fleet

  In order to manage my vehicles fleet
  As an application user
  I should be able to create a fleet

  @critical
  Scenario: I can create a fleet
    Given my user id
    When I create my fleet
    Then my fleet should be created

  Scenario: I can't create more than one fleet
    Given my user id
    When I create my fleet
    And I have already created my fleet
    Then I should be informed that I already have a fleet