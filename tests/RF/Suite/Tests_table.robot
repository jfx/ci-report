*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          ../Suite/Function/suite.txt

*** Test Cases ***
Table should have a header
    When I go to suite page    &{P1C4S1}
    Then The table header should contain values    t-test    Package / Class / Name    Status    Duration    Message

Table should list all suites
    When I go to suite page    &{P1C4S1}
    Then The table should contain X rows    t-test    13
    And The table column should contain values    t-test    1    Test 0 in p1c4s1-suite    Test 12 in p1c4s1-suite

The first row should be the first test
    When I go to suite page    &{P1C4S1}
    Then The table row should contain values    t-test    1    Test 0 in p1c4s1-suite    Passed    1s

Check passed value cell
    When I go to suite page    &{P1C4S1}
    Then Element Should Contain    s-test-1    Passed
    And Page Should Contain Element    xpath=//span[@id="s-test-1" and @class="text-success"]

Check failed value cell
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-96    Failed
    And Page Should Contain Element    xpath=//span[@id="s-test-96" and @class="text-danger"]

Check errored value cell
    When I go to suite page    &{P4C2S1}
    Then Element Should Contain    s-test-81    Errored
    And Page Should Contain Element    xpath=//span[@id="s-test-81" and @class="text-danger"]

Check skipped value cell
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-100    Skipped
    And Page Should Contain Element    xpath=//span[@id="s-test-100" and @class="text-warning"]

A row test with "Passed" status should have green background color
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-1" and @class="table-success"]

A row test with "Skipped" status should have yellow background color
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-100" and @class="table-warning"]

A row test with "Failed" should have red background color
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-96" and @class="table-danger"]

A row test with "Errored" should have red background color
    When I go to suite page    &{P4C2S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-81" and @class="table-danger"]

A suite without test should display "No test"
    When I go to suite page    &{P8C1S1}
    Then Page Should Contain    No test
