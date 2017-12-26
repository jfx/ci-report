*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/home.txt
Resource          ../Campaign/Function/campaign.txt

*** Test Cases ***
Table should have a header
    When I go to Home
    Then The table header should contain values    t-project    Project name    Last campaign    Passed tests

Table should list all projects
    When I go to Home
    Then The table should contain X rows    t-project    7
    And The table column should contain values    t-project    1    Project Eight    Project Five    Project Four

The first row should be the first project in alphabetical order
    When I go to Home
    Then Table Cell Should Contain    t-project    2    1    Project Eight

Click on project name should go to project page
    Given I go to Home
    When Click Link    a-project-${P1C4.prefid}
    Then I should be on project dashboard    ${P1C4.prefid}

Click on tests ratio should go to campaign page
    Given I go to Home
    When Click Link    a-campaign-${P1C4.prefid}
    Then I should be on campaign page    &{P1C4}

Check "Unknown" status cell
    When I go to Home
    Then Element Should Contain    s-status-${P7C1.prefid}    Unknown

Check "Success" status cell
    When I go to Home
    Then Element Should Contain    s-status-${P5C4.prefid}    Success

Check "Failure" status cell
    When I go to Home
    Then Element Should Contain    s-status-${P4C3.prefid}    Failure

Check "Warning" status cell
    When I go to Home
    Then Element Should Contain    s-status-${P3C3.prefid}    Warning

Check "Passed tests" value cell with no run test
    When I go to Home
    Then Element Should Contain    a-campaign-${P7C1.prefid}    0 / 0

Check "Passed tests" value cell
    When I go to Home
    ${enabled} =    Evaluate    ${P4C3.passed}+${P4C3.failed}+${P4C3.errored}+${P4C3.skipped}
    Then Element Should Contain    a-campaign-${P4C3.prefid}    ${P4C3.passed} / ${enabled}

A row with last campaign with no test should have grey background color
    When I go to Home
    Then Page Should Contain Element    xpath=//tr[@id="tr-project-${P7C1.prefid}" and @class="table-secondary"]

A row with last campaign with success status should have green background color
    When I go to Home
    Then Page Should Contain Element    xpath=//tr[@id="tr-project-${P5C4.prefid}" and @class="table-success"]

A row with last campaign with warning status should have yellow background color
    When I go to Home
    Then Page Should Contain Element    xpath=//tr[@id="tr-project-${P3C3.prefid}" and @class="table-warning"]

A row with last campaign with failure status should have red background color
    When I go to Home
    Then Page Should Contain Element    xpath=//tr[@id="tr-project-${P4C3.prefid}" and @class="table-danger"]
