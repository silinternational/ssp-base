Feature: Ensure I can login to Sp1 through Idp1, must login to Sp2 through Idp2 and am already logged in for Sp3.

  Scenario: Login to SP1 through IDP1
    When I go to the SP1 login page
      And the url should match "sildisco/disco.php"
      And I should see "to continue to SP1"
      And I click on the "IDP 1" tile
      And I log in using my "IDP 1" credentials
    Then I should see my attributes on SP1

  Scenario: After IDP1 login, go to SP2 through IDP2
    Given I have authenticated with IDP1 for SP1
    When I go to the SP2 login page
    And I log in using my "IDP 2" credentials
    Then I should see my attributes on SP2

  Scenario: After IDP1 login, go directly to SP3 without credentials
    Given I have authenticated with IDP1 for SP1
    When I go to the SP3 login page
      And the url should match "sildisco/disco.php"
      And I should see "to continue to SP3"
      And I click on the "IDP 1" tile
    Then I should see my attributes on SP3

  Scenario: Logout of IDP1
    Given I have authenticated with IDP1 for SP1
    When I log out of IDP1
    Then I should see "You have been logged out."

  Scenario: Logout of IDP2
    Given I have authenticated with IDP2 for SP2
    When I log out of IDP2
    Then I should see "You have been logged out."
