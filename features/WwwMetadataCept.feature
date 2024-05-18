Feature: Ensure I see the hub's metadata page.

  Scenario: Show the hub's metadata page in default format
    When I go to "http://hub4tests/module.php/sildisco/metadata.php"
    Then I should see "$metadata['hub4tests']"

  Scenario: Show the hub's metadata page in XML format
    When I go to "http://hub4tests/module.php/sildisco/metadata.php?format=xml"
    Then I should see the metadata in XML format

  Scenario: Show the hub's metadata page PHP format
    When I go to "http://hub4tests/module.php/sildisco/metadata.php?format=php"
    Then I should see "$metadata['hub4tests']"
