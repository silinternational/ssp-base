Feature: Expiry Checker module
  Background:
    Given I go to the SP1 login page
      And I click on the "IDP 1" tile
  
  Scenario: Password is not about to expire
    When I log in as a user who's password is NOT about to expire
    Then I should see a page indicating that I successfully logged in
    
  Scenario: Password is about to expire
    When I log in as a user who's password is about to expire
    Then I should see a page warning me that my password is about to expire
    
  Scenario: Password has expired
    When I log in as a user who's password has expired
    Then I should see a page telling me that my password has expired
