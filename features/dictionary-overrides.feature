Feature: Applying dictionary overrides
  
  Scenario: Successfully merging dictionary files
    Given an original "mfa.definition.json" file containing
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
      And an override "mfa.definition.json" file containing
        """
        {
          "webauthn_header": {
            "en": "Security key or fingerprint",
          }
        }
        """
    When I apply the dictionary overrides
    Then the original "mfa.definition.json" file will end up containing
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
