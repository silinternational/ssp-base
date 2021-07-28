Feature: Expiry Checker module
  
  Scenario: Password is not about to expire
    Given I go to the SP1 login page
      And I click on the "IDP 1" tile
    When I log in as a user who's password is NOT about to expire
    Then I should see a page indicating that I successfully logged in
    
  Scenario: Password is about to expire
    
  Scenario: Password has expired
