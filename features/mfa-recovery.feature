Feature: Send a code to an MFA recovery contact

  Background:
    Given I go to the SP1 login page

  Scenario: Offer 2SV recovery when we have recovery config
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials that have backup codes
    When I log in
    Then I should see a link to send a code to a recovery contact
