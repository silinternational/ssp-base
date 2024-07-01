Feature: Ensure I can login to Sp2 through Idp2, get discovery page for Sp1, and must login to Sp3 through Idp1.

  Scenario: Login to SP2 through IDP2
    When I go to the SP2 login page
    And I log in using my "IDP 2" credentials
    Then I should see my attributes on SP2

  Scenario: Get discovery page for SP1
    Given I have authenticated with IDP2 for SP2
    When I go to the SP1 login page
    And the url should match "sildisco/disco.php"
    And I click on the "IDP 2" tile
    Then I should see my attributes on SP1

  Scenario: Must login to SP3 through IDP1
    Given I have authenticated with IDP2 for SP2
    When I go to the SP3 login page
    And the url should match "sildisco/disco.php"
    And I click on the "IDP 1" tile
    And I log in using my "IDP 1" credentials
    Then I should see my attributes on SP3

