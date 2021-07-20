Feature: Material theme
  
  Scenario: Hub page
    When I go to the Hub's discovery page
    Then I should see our material theme
  
  Scenario: Error page
    When I go to the Hub but specify an invalid authentication source
    Then I should see an "Error" page
      And I should see our material theme

  Scenario: Logout page
    When I go to the Hub's home page
      And I click on "Authentication"
      And I click on "Test configured authentication sources"
      And I click on "admin"
      And I log in as a hub administrator
      And I click on "Logout"
    Then I should see a "Logged out" page
      And I should see our material theme

  Scenario: Login page
    When I go to the Hub's discovery page
      And I click on the "IDP 1" tile
    Then I should see a "Login" page
      And I should see our material theme
