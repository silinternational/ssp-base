Feature: Ensure I can login to Sp1 through Idp2, am already logged in for Sp2, and must login to Sp3 through Idp1.

  Scenario: Login to SP1 through IDP2
    When I go to the SP1 login page
     And the url should match "sildisco/disco.php"
     And I should see "to continue to SP1"
     And I click on the "IdP 2" tile
     And I login using password "b"
    Then I should see "test_admin@idp2.org"

  Scenario: After IDP2 login, go directly to SP2 without credentials
    Given I have authenticated with IDP2 for SP1
    When I go to the SP2 login page
    Then I should see "test_admin@idp2.org"

  Scenario: After IDP2 login, go to SP3 through IDP1
    Given I have authenticated with IDP2 for SP1
    When I go to the SP3 login page
     And I should see "to continue to SP3"
     And I click on the "IdP 1" tile
     And I should see "Enter your username and password"
     And I login using password "a"
    Then I should see "test_admin@idp1.org"
