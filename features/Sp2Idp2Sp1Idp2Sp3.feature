Feature: Ensure I can login to Sp2 through Idp2, get discovery page for Sp1, and must login to Sp3 through Idp1.

  Scenario: Login to SP2 through IDP2
    When I go to the SP2 login page
     And I should see "Enter your username and password"
     And I login using password "b"
    Then I should see "test_admin@idp2.org"

  Scenario: Get discovery page for SP1
    Given I have authenticated with IDP2 for SP2
    When I go to the SP1 login page
     And the url should match "sildisco/disco.php"
     And I click on the "IdP 2" tile
    Then I should see "test_admin@idp2.org"

  Scenario: Must login to SP3 through IDP1
    Given I have authenticated with IDP2 for SP2
    When I go to the SP3 login page
     And the url should match "sildisco/disco.php"
     And I click on the "IdP 1" tile
     And I should see "Enter your username and password"
     And I login using password "a"
    Then I should see "test_admin@idp1.org"

