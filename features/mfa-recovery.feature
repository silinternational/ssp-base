Feature: Send a code to an MFA recovery contact

  Background:
    Given I go to the SP1 login page

  Scenario: Offer 2SV recovery when we have recovery config
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials that have backup codes
    When I log in
    Then I should see a link to send a code to a recovery contact

  Scenario: User with manager, show manager and recovery contact as options
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials that have backup codes
    And I log in
    When I click the link to send an MFA code to a recovery contact
    Then I should see a way to send an MFA recovery code to my manager
    And I should see a way to send an MFA recovery code to another recovery contact
