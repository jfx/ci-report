*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Table should have a header
    When I go to project dahsboard    ${P1.prefid}
    Then The table header should contain values    t-campaign    \#Id    %    Passed    Failed    Errored
    ...    Skipped    Disabled    Start    End    Document

Table should list all campaigns
    When I go to project dahsboard    ${P1.prefid}
    Then The table should contain X rows    t-campaign    4
    And The table column should contain values    t-campaign    1    \#1    \#2    \#3    \#4

The first row should be the last campaign
    When I go to project dahsboard    ${P1C4.prefid}
    Then The table row should contain values of campaign    1    &{P1C4}

Click on #Id should go to campaign page
    Given I go to project dahsboard    ${P1C4.prefid}
    When Click Link    a-campaign-${P1C4.crefid}
    Then I should be on campaign page    &{P1C4}

Check failed value cell
    When I go to project dahsboard    ${P2C3.prefid}
    Then I check table cell value    t-campaign    1    4    ${P2C3.failed}

Check errored value cell
    When I go to project dahsboard    ${P4C3.prefid}
    Then I check table cell value    t-campaign    1    5    ${P4C3.errored}

Check skipped value cell
    When I go to project dahsboard    ${P2C3.prefid}
    Then I check table cell value    t-campaign    1    6    ${P2C3.skipped}

Check disabled value cell
    When I go to project dahsboard    ${P5C4.prefid}
    Then I check table cell value    t-campaign    1    7    ${P5C4.disabled}

A row campaign with more or equal 95% should have green background color
    When I go to project dahsboard    ${P1C4.prefid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P1C4.crefid}" and @class="table-success"]

A row campaign between 80% and 95% should have yellow background color
    When I go to project dahsboard    ${P3C3.prefid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P3C3.crefid}" and @class="table-warning"]

A row campaign under 80% should have red background color
    When I go to project dahsboard    ${P4C3.prefid}
    Then Page Should Contain Element    xpath=//tr[@id="tr-campaign-${P4C3.crefid}" and @class="table-danger"]

A project without campaign should display "No campaign"
    When I go to project dahsboard    ${P6.prefid}
    Then Page Should Contain    No campaign
