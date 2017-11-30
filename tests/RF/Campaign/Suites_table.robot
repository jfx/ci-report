*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Table should have a header
    When I go to campaign page    &{P1C4}
    Then The table header should contain values    t-suite    \#Id    Name    %    Passed    Failed
    ...    Errored    Skipped    Disabled    Duration    Time    Document

Table should list all suites
    When I go to campaign page    &{P1C4}
    Then The table should contain X rows    t-suite    2
    And The table column should contain values    t-suite    1    \#1    \#2

The first row should be the first suite
    When I go to campaign page    &{P1C4}
    Then The table row should contain values of suite    1    &{P1C4S1}

Click on #Id should go to campaign page
    Given I go to campaign page    &{P1C4}
    When Click Link    a-suite-${P1C4S1.srefid}
    Then I should be on suite page    &{P1C4S1}

Check failed value cell
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    5    ${P2C3S1.failed}

Check errored value cell
    When I go to campaign page    &{P4C2}
    Then I check table cell value    t-suite    1    6    ${P4C2S1.errored}

Check skipped value cell
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    7    ${P2C3S1.skipped}

Check disabled value cell
    When I go to campaign page    &{P5C4}
    Then I check table cell value    t-suite    1    8    ${P5C4S1.disabled}

A row suite with more or equal 95% should have green background color
    When I go to campaign page    &{P1C4}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P1C4S1.srefid}" and @class="table-success"]

A row suite between 80% and 95% should have yellow background color
    When I go to campaign page    &{P3C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P3C3S1.srefid}" and @class="table-warning"]

A row suite under 80% should have red background color
    When I go to campaign page    &{P4C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P4C3S1.srefid}" and @class="table-danger"]

A row suite with no test should have grey background color
    When I go to campaign page    &{P8C1S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P8C1S1.srefid}" and @class="table-secondary"]

A campaign without suite should display "No suite"
    When I go to campaign page    &{P7C1}
    Then Page Should Contain    No suite
