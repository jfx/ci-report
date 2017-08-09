*** Settings ***
Resource          ../Common/Function/common.txt
Resource          ../Common/Function/check.txt
Resource          Function/dashboard.txt
Resource          ../Campaign/Function/campaign.txt

*** Test Cases ***
Table should have a header
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then The table header should contain values    t-campaign    \#Id    %    Passed    Failed    Errored
    ...    Skipped    Disabled    Duration    Time    Document
    [Teardown]    Teardown

Table should list all campaigns
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then The table should contain X rows    t-campaign    4
    And The table column should contain values    t-campaign    1    \#1    \#2    \#3    \#4
    [Teardown]    Teardown

The first row should be the last campaign
    [Setup]    Setup
    When I go to project dahsboard    ${P1C4.pid}
    Then The table row should contain values of campaign    1    &{P1C4}
    [Teardown]    Teardown

Click on #Id should go to campaign page
    [Setup]    Setup
    Given I go to project dahsboard    ${P1C4.pid}
    When Click Link    a-campaign-${P1C4.id}
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown

Check failed value cell
    [Setup]    Setup
    When I go to project dahsboard    ${P2C3.pid}
    Then I check table cell value    t-campaign    1    4    ${P2C3.failed}
    [Teardown]    Teardown

Check errored value cell
    [Setup]    Setup
    When I go to project dahsboard    ${P4C3.pid}
    Then I check table cell value    t-campaign    1    5    ${P4C3.errored}
    [Teardown]    Teardown

Check skipped value cell
    [Setup]    Setup
    When I go to project dahsboard    ${P2C3.pid}
    Then I check table cell value    t-campaign    1    6    ${P2C3.skipped}
    [Teardown]    Teardown

Check disabled value cell
    [Setup]    Setup
    When I go to project dahsboard    ${P5C4.pid}
    Then I check table cell value    t-campaign    1    7    ${P5C4.disabled}
    [Teardown]    Teardown

A row campaign with more or equal 95% should have successfull color
    [Setup]    Setup
    When I go to project dahsboard    ${P1C4.pid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P1C4.id}" and @class="table-success"]
    [Teardown]    Teardown

A row campaign between 80% and 95% should have warning color
    [Setup]    Setup
    When I go to project dahsboard    ${P3C3.pid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P3C3.id}" and @class="table-warning"]
    [Teardown]    Teardown

A row campaign under 80% should have failed color
    [Setup]    Setup
    When I go to project dahsboard    ${P4C3.pid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P4C3.id}" and @class="table-danger"]
    [Teardown]    Teardown
