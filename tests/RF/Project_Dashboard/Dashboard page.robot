*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Title of window should contain project name
    When I go to project dahsboard    ${P1.refid}
    Then Title Should Be    ci-report / ${P1.name}

Project with no campaign should display only empty campaign table
    When I go to project dahsboard    ${P6.refid}
    Then Page Should Not Contain Element    xpath=//div[@class="card-block"]
    And The table should contain X rows    t-campaign    1
    And I check table cell value    t-campaign    1    1    No campaign
