*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/suite.txt

*** Test Cases ***
Suite values should be displayed
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Element Should Contain    css=p.card-text    \#${P1C4S1.id}
    And I check suite displayed values    &{P1C4S1}
    [Teardown]    Teardown

Suite with more or equal than 95% should be successfull
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]
    [Teardown]    Teardown

Suite between 80% and 95% should be with a warning status
    [Setup]    Setup
    When I go to suite page    &{P3C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]
    [Teardown]    Teardown

Suite under 80% should be with failed
    [Setup]    Setup
    When I go to suite page    &{P4C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]
    [Teardown]    Teardown

Suite with errored tests should display values
    [Setup]    Setup
    When I go to suite page    &{P4C3S1}
    Then I check suite displayed values    &{P4C3S1}
    [Teardown]    Teardown

Suite with skipped tests should display values
    [Setup]    Setup
    When I go to suite page    &{P2C3S1}
    Then I check suite displayed values    &{P2C3S1}
    [Teardown]    Teardown

Suite with disabled tests should display values
    [Setup]    Setup
    When I go to suite page    &{P5C4S1}
    Then I check suite displayed values    &{P5C4S1}
    [Teardown]    Teardown

Suite with only 50% disabled tests should be successfull at 100%
    [Setup]    Setup
    When I go to suite page    &{P5C4S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50
    [Teardown]    Teardown
