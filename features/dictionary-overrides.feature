Feature: Applying dictionary overrides
  
  Scenario: Successfully merging dictionary files
    Given a "/tmp/test/mfa.definition.json" file containing
        """
        {
          "title": {
            "en": "2-Step Verification",
          },
          "webauthn_header": {
            "en": "Security key",
          }
        }
        """
      And a "/tmp/test/overrides/mfa.definition.json" file containing
        """
        {
          "webauthn_header": {
            "en": "Security key or fingerprint",
          }
        }
        """
    When I go to the "/tmp/test/overrides" folder and apply the dictionary overrides
    Then the "/tmp/test/mfa.definition.json" file should contain
        """
        {
          "title": {
            "en": "2-Step Verification",
          },
          "webauthn_header": {
            "en": "Security key or fingerprint",
          }
        }
        """
