*** Settings ***
Resource          ../Common/Function/common.txt
Resource          ../Common/Function/check.txt
Resource          ../Suite/Function/suite.txt

*** Test Cases ***
Table should have a header
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then The table header should contain values    t-test    Package / Class / Name    Status    Duration    Message
    [Teardown]    Teardown

Table should list all suites
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then The table should contain X rows    t-test    13
    And The table column should contain values    t-test    1    Test 0 in p1c4s1-suite    Test 12 in p1c4s1-suite
    [Teardown]    Teardown

The first row should be the first test
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then The table row should contain values    t-test    1    Test 0 in p1c4s1-suite    Passed    1s
    [Teardown]    Teardown

Check passed value cell
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Element Should Contain    s-test-1    Passed
    And Page Should Contain Element    xpath=//span[@id="s-test-1" and @class="text-success"]
    [Teardown]    Teardown

Check failed value cell
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-96    Failed
    And Page Should Contain Element    xpath=//span[@id="s-test-96" and @class="text-danger"]
    [Teardown]    Teardown

Check errored value cell
    [Setup]    Setup
    When I go to suite page    &{P4C2S1}
    Then Element Should Contain    s-test-81    Errored
    And Page Should Contain Element    xpath=//span[@id="s-test-81" and @class="text-danger"]
    [Teardown]    Teardown

Check skipped value cell
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-100    Skipped
    And Page Should Contain Element    xpath=//span[@id="s-test-100" and @class="text-warning"]
    [Teardown]    Teardown

A row test with "Passed" status should have green background color
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-1" and @class="table-success"]
    [Teardown]    Teardown

A row test with "Skipped" status should have yellow background color
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-100" and @class="table-warning"]
    [Teardown]    Teardown

A row test with "Failed" should have red background color
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-96" and @class="table-danger"]
    [Teardown]    Teardown

A row test with "Errored" should have red background color
    [Setup]    Setup
    When I go to suite page    &{P4C2S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-81" and @class="table-danger"]
    [Teardown]    Teardown
