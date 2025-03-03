Feature: Send a code to an MFA recovery contact

  Background:
    Given I go to the SP1 login page
    And I click on the "IDP 1" tile

  Scenario: Offer 2SV recovery when we have recovery config
    Given I provide credentials that have backup codes
    And we have recovery-contacts config
    When I log in
    Then I should see a link to send a code to a recovery contact
