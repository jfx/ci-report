*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt
Resource          ../Campaign/Function/campaign.txt

*** Test Cases ***
Last campaign for a project should be displayed
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then Element Should Contain    css=p.card-text    \#${P1C4.id}
    [Teardown]    Teardown

Last campaign for project with more or equal than 95% should be successfull
    [Setup]    Setup
    When I go to project dahsboard    ${P2C3.pid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-success"]    Details
    [Teardown]    Teardown

Last campaign for project between 80% and 95% should be with a warning status
    [Setup]    Setup
    When I go to project dahsboard    ${P3C3.pid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-warning"]    Details
    [Teardown]    Teardown

Last campaign for project under 80% should be with failed
    [Setup]    Setup
    When I go to project dahsboard    ${P4C3.pid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]
    And Element Should Contain    xpath=//a[@class="btn btn-outline-danger"]    Details
    [Teardown]    Teardown

Last campaign none zeros values for project should be displayed
    [Setup]    Setup
    When I go to project dahsboard    ${P1C4.pid}
    Then I check last campaign displayed values    &{P1C4}
    [Teardown]    Teardown

Last campaign with errored tests should display values
    [Setup]    Setup
    When I go to project dahsboard    ${P4C3.pid}
    Then I check last campaign displayed values    &{P4C3}
    And Page Should Contain Element    p-errored
    [Teardown]    Teardown

Last campaign with skipped tests should display values
    [Setup]    Setup
    When I go to project dahsboard    ${P2C3.pid}
    Then I check last campaign displayed values    &{P2C3}
    And Page Should Contain Element    p-skipped
    [Teardown]    Teardown

Last campaign with disabled tests should display values
    [Setup]    Setup
    When I go to project dahsboard    ${P5C4.pid}
    Then I check last campaign displayed values    &{P5C4}
    And Page Should Contain Element    p-disabled
    [Teardown]    Teardown

Last campaign with only 50% disabled tests should be successfull at 100%
    [Setup]    Setup
    When I go to project dahsboard    ${P5C4.pid}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50
    [Teardown]    Teardown

Button Details should go to last campaign page
    [Setup]    Setup
    Given I go to project dahsboard    ${P1C4.pid}
    When Click Link    a-details
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown
