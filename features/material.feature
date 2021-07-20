Feature: Material theme
  
  Scenario: Hub page
    When I go to the Hub's discovery page
    Then I should see our material theme
  
  Scenario: Error page
    When I go to the Hub but specify an invalid authentication source
    Then I should see an error page
      And I should see our material theme
