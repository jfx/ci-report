*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt
Resource          ../Common/Function/check.txt

*** Test Cases ***
Title of window should contain project name
    [Setup]    Setup
    When I go to project dahsboard    ${P1.refid}
    Then Title Should Be    ci-report / ${P1.name}
    [Teardown]    Teardown

Project with no campaign should display only empty campaign table
    [Setup]    Setup
    When I go to project dahsboard    ${P6.refid}
    Then Page Should Not Contain Element    xpath=//div[@class="card-block"]
    And The table should contain X rows    t-campaign    1
    And I check table cell value    t-campaign    1    1    No campaign
    [Teardown]    Teardown
