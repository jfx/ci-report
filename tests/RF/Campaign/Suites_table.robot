*** Settings ***
Resource          ../Common/Function/common.txt
Resource          ../Common/Function/check.txt
Resource          Function/campaign.txt
Resource          ../Suite/Function/suite.txt

*** Test Cases ***
Table should have a header
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then The table header should contain values    t-suite    \#Id    Name    %    Passed    Failed
    ...    Errored    Skipped    Disabled    Duration    Time    Document
    [Teardown]    Teardown

Table should list all suites
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then The table should contain X rows    t-suite    2
    And The table column should contain values    t-suite    1    \#1    \#2
    [Teardown]    Teardown

The first row should be the first suite
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then The table row should contain values of suite    1    &{P1C4S1}
    [Teardown]    Teardown

Click on #Id should go to campaign page
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When Click Link    a-suite-${P1C4S1.id}
    Then I should be on suite page    &{P1C4S1}
    [Teardown]    Teardown

Check failed value cell
    [Setup]    Setup
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    5    ${P2C3S1.failed}
    [Teardown]    Teardown

Check errored value cell
    [Setup]    Setup
    When I go to campaign page    &{P4C2}
    Then I check table cell value    t-suite    1    6    ${P4C2S1.errored}
    [Teardown]    Teardown

Check skipped value cell
    [Setup]    Setup
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    7    ${P2C3S1.skipped}
    [Teardown]    Teardown

Check disabled value cell
    [Setup]    Setup
    When I go to campaign page    &{P5C4}
    Then I check table cell value    t-suite    1    8    ${P5C4S1.disabled}
    [Teardown]    Teardown

A row suite with more or equal 95% should have green background color
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P1C4S1.id}" and @class="table-success"]
    [Teardown]    Teardown

A row suite between 80% and 95% should have yellow background color
    [Setup]    Setup
    When I go to campaign page    &{P3C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P3C3S1.id}" and @class="table-warning"]
    [Teardown]    Teardown

A row suite under 80% should have red background color
    [Setup]    Setup
    When I go to campaign page    &{P4C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P4C3S1.id}" and @class="table-danger"]
    [Teardown]    Teardown
