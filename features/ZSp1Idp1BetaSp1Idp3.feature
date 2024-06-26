Feature: Ensure I don't see IdP 3 at first, but after I go to the Beta Tester page I can see and login through IdP 3.

  Scenario: Normally the IdP3 is disabled
    When I go to the "SP1" login page
    And the url should match "sildisco/disco.php"
    Then the "div" element should contain "IdP 3 coming soon"

  Scenario: After going to the "Beta Test" page, IdP3 is available for use
    When I go to "http://ssp-hub.local/module.php/sildisco/betatest.php"
    And I go to the "SP1" login page
    And I click on the "IDP 3" tile
    And I log in using my "IDP 3" credentials
    Then I should see "test_admin@idp3.org"
