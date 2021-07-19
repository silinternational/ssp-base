Feature: Material theme
  
  Scenario: Hub page
    When I go to the Hub's discovery page
    Then I should see our material theme
  
  Scenario: Error page
    When I go to the Hub's home page
      And I click the "Federation" tab
      And I click a "Show metadata" link
      And I log in as a hub administrator
    Then I should see an error message
      And I should see our material theme
