Feature: Status check

  Scenario: Good status check
    When I check the status of this module
    Then I should get back an "OK" with an HTTP status code of "200"

  Scenario: Request initial login page
    When I request the initial login page of this module
    Then I should get back an HTTP status code of "200"
