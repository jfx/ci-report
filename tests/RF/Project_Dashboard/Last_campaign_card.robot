*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Last campaign for a project should be displayed
    When I go to project dahsboard    ${P1.prefid}
    Then Element Should Contain    css=p.card-text    \#${P1C4.crefid}

Last campaign for project with more or equal than 95% should be successfull
    When I go to project dahsboard    ${P2C3.prefid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-success"]    Details

Last campaign for project between 80% and 95% should be with a warning status
    When I go to project dahsboard    ${P3C3.prefid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-warning"]    Details

Last campaign for project under 80% should be with failed
    When I go to project dahsboard    ${P4C3.prefid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-danger"]    Details

Last campaign none zeros values for project should be displayed
    When I go to project dahsboard    ${P1C4.prefid}
    Then I check last campaign displayed values    &{P1C4}

Last campaign with errored tests should display values
    When I go to project dahsboard    ${P4C3.prefid}
    Then I check last campaign displayed values    &{P4C3}
    And Page Should Contain Element    p-errored

Last campaign with skipped tests should display values
    When I go to project dahsboard    ${P2C3.prefid}
    Then I check last campaign displayed values    &{P2C3}
    And Page Should Contain Element    p-skipped

Last campaign with disabled tests should display values
    When I go to project dahsboard    ${P5C4.prefid}
    Then I check last campaign displayed values    &{P5C4}
    And Page Should Contain Element    p-disabled

Last campaign with only 50% disabled tests should be successfull at 100%
    When I go to project dahsboard    ${P5C4.prefid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50

Campaign with end date should display it
    When I go to project dahsboard    ${P2C3.prefid}
    Then Element Should Contain    p-end    ${P2C3.end}
    And Element Should Not Contain    p-end    -

Campaign without end date should "-"
    When I go to project dahsboard    ${P1.prefid}
    Then Element Should Contain    p-end    -

Button Details should go to last campaign page
    Given I go to project dahsboard    ${P1C4.prefid}
    When Click Link    a-details
    Then I should be on campaign page    &{P1C4}
