Feature: Ensure I can login to Sp3 through Idp1, get the discovery page for Sp1 and must login to Sp2 through Idp2.

  Scenario: login to SP3 using IDP1
    When I go to the SP3 login page
    And the url should match "sildisco/disco.php"
    And I should see "to continue to SP3"
    And I click on the "IDP 1" tile
    And I log in using my "IDP 1" credentials
    Then I should see my attributes on SP3

  Scenario: having authenticated with IDP1 for SP3, go to SP1 via the discovery page
    Given I have authenticated with IDP1 for SP3
    When I go to the SP1 login page
    And the url should match "sildisco/disco.php"
    And I click on the "IDP 1" tile
    Then I should see my attributes on SP1

  Scenario: having authenticated with IDP1 for SP3, login to SP2 using IDP2
    Given I have authenticated with IDP1 for SP3
    When I go to the SP2 login page
    And I log in using my "IDP 2" credentials
    Then I should see my attributes on SP2
