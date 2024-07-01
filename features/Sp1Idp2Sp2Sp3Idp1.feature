Feature: Ensure I can login to Sp1 through Idp2, am already logged in for Sp2, and must login to Sp3 through Idp1.

  Scenario: Login to SP1 through IDP2
    When I go to the SP1 login page
    And the url should match "sildisco/disco.php"
    And I should see "to continue to SP1"
    And I click on the "IDP 2" tile
    And I log in using my "IDP 2" credentials
    Then I should see my attributes on SP1

  Scenario: After IDP2 login, go directly to SP2 without credentials
    Given I have authenticated with IDP2 for SP1
    When I go to the SP2 login page
    Then I should see my attributes on SP2

  Scenario: After IDP2 login, go to SP3 through IDP1
    Given I have authenticated with IDP2 for SP1
    When I go to the SP3 login page
    And I should see "to continue to SP3"
    And I click on the "IDP 1" tile
    And I log in using my "IDP 1" credentials
    Then I should see my attributes on SP3
