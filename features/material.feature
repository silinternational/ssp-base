Feature: Material theme

  Scenario: Hub (disco) page
    When I go to the Hub's discovery page
    And I log in as a hub administrator
    Then I should see our material theme

  Scenario: Error page
    When I go to the Hub but specify an invalid authentication source
    And I log in as a hub administrator
    Then I should see an "Error" page
    And I should see our material theme

    # TODO: if this is really used, fix it. If not, delete the test, the template, and the translation file.
    #   (The reason this fails is because there is no "Logout" button on the new admin interface)
#  Scenario: Logout page
#    When I go to the Hub's home page
#    And I log in as a hub administrator
#    And I click on "Logout"
#    Then I should see a "Logged out" page
#    And I should see our material theme

  Scenario: Login page
    When I go to the SP1 login page
    And I click on the "IDP 2" tile
    Then I should see a "Login with your IDP 2 identity" page
    And I should see our material theme
