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
    And I provide credentials that have backup codes and a manager
    And I log in
    When I click the Request Assistance link
    Then I should see a way to send an MFA recovery code to my manager
    And I should see a way to send an MFA recovery code to another recovery contact

  Scenario Outline: User with manager and recovery contact, send code to each
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials that have backup codes and a manager
    And I log in
    And I click the Request Assistance link
    When I send the code to the <recipient>
    Then I should not see an error message
    And I should see confirmation that the code was sent

    Examples:
      | recipient        |
      | recovery contact |
      | manager          |

  Scenario: Abbreviate recovery contact names
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials for a user with a manager and a recovery contact
    And I log in
    When I click the Request Assistance link
    Then I should see "your manager" as one of the recovery contact options
    And I should see the abbreviated, not full, name of my recovery contact as an option

  Scenario: Recovery contacts API provides no contacts
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials for a user with a manager but no recovery contacts
    And I log in
    When I click the Request Assistance link
    Then I should see a way to send an MFA recovery code to my manager
    And I should see a way to send an MFA recovery code to the fallback recovery contact

  Scenario: Recovery contacts API provides a contact
    Given I use an IDP that is configured to offer MFA recovery-contacts
    And I provide credentials for a user with a manager and a recovery contact
    And I log in
    When I click the Request Assistance link
    Then I should see a way to send an MFA recovery code to my manager
    And I should see a way to send an MFA recovery code to this account's recovery contact
