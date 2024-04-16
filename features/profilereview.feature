Feature: Prompt to review profile information

  Scenario: Don't ask for review
    Given I provide credentials that do not need review
    When I login
    Then I should end up at my intended destination

  Scenario Outline: Present reminder as required by the user profile
    Given I provide credentials that are due for a <category> <nag type> reminder
    When I login
    Then I should see the message: <message>
    And there should be a way to go update my profile now
    And there should be a way to continue to my intended destination

    Examples:
      | category | nag type | message                          |
      | mfa      | add      | "2-Step Verification"            |
      | method   | add      | "alternate email addresses"      |
      | profile  | review   | "Please take a moment to review" |

  Scenario Outline: Obeying a reminder
    Given I provide credentials that are due for a <category> <nag type> reminder
    And I have logged in
    When I click the update profile button
    Then I should end up at the update profile URL

    Examples:
      | category | nag type |
      | mfa      | add      |
      | method   | add      |
      | profile  | review   |

  Scenario Outline: Ignoring a reminder
    Given I provide credentials that are due for a <category> <nag type> reminder
    And I have logged in
    When I click the remind-me-later button
    Then I should end up at my intended destination

    Examples:
      | category | nag type |
      | mfa      | add      |
      | method   | add      |
      | profile  | review   |

  Scenario: Ensuring that manager mfa data is not displayed to the user
    Given I provide credentials for a user that has used the manager mfa option
    And I have logged in
    Then I should see the message: "Please take a moment to review"
    And I should not see any manager mfa information
