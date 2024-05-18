Feature: Ensure I can login to Sp3 through Idp1, get the discovery page for Sp1 and must login to Sp2 through Idp2.

  Scenario: login to SP3 using IDP1
    When I go to the SP3 login page
     And the url should match "sildisco/disco.php"
     And I should see "to continue to SP3"
     And I click on the "IdP 1" tile
     And I should see "Enter your username and password"
     And I login using password "a"
    Then I should see "test_admin@idp1.org"

  Scenario: having authenticated with IDP1 for SP3, go to SP1 via the discovery page
    Given I have authenticated with IDP1 for SP3
    When I go to the SP1 login page
     And the url should match "sildisco/disco.php"
     And I click on the "IdP 1" tile
    Then I should see "test_admin@idp1.org"

  Scenario: having authenticated with IDP1 for SP3, login to SP2 using IDP2
    Given I have authenticated with IDP1 for SP3
    When I go to the SP2 login page
     And I should see "Enter your username and password"
     And I login using password "b"
    Then I should see "test_admin@idp2.org"
