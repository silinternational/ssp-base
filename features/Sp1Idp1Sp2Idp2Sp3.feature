Feature: Ensure I can login to Sp1 through Idp1, must login to Sp2 through Idp2 and am already logged in for Sp3.

  Scenario: Login to SP1 through IDP1
    When I go to the SP1 login page
      And the url should match "sildisco/disco.php"
      And I should see "to continue to SP1"
      And I click on the "IdP 1" tile
      And I login using password "a"
    Then I should see "test_admin@idp1.org"

  Scenario: After IDP1 login, go to SP2 through IDP2
    Given I have authenticated with IDP1 for SP1
    When I go to the SP2 login page
      And I should see "Enter your username and password"
      And I login using password "b"
    Then I should see "test_admin@idp2.org"

  Scenario: After IDP1 login, go directly to SP3 without credentials
    Given I have authenticated with IDP1 for SP1
    When I go to the SP3 login page
      And the url should match "sildisco/disco.php"
      And I should see "to continue to SP3"
      And I click on the "IdP 1" tile
    Then I should see "test_admin@idp1.org"

  Scenario: Logout of IDP1
    Given I have authenticated with IDP1 for SP1
    When I log out of IDP1
    Then I should see "You have been logged out."

  Scenario: Logout of IDP2
    Given I have authenticated with IDP2 for SP2
    When I log out of IDP2
    Then I should see "You have been logged out."
