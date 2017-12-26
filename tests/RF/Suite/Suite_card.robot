*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/suite.txt

*** Test Cases ***
Suite values should be displayed
    When I go to suite page    &{P1C4S1}
    Then Element Should Contain    css=p.card-text    ${P1C4S1.name}
    And I check suite displayed values    &{P1C4S1}

Suite with more or equal than 95% should be successfull
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]

Suite between 80% and 95% should have a warning status
    When I go to suite page    &{P3C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]

Suite under 80% should have a failed status
    When I go to suite page    &{P4C3S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]

Suite with no test should have an unknown status
    When I go to suite page    &{P8C1S1}
    Then Page Should Contain Element    xpath=//h4[@class="card-title text-secondary"]
    And Page Should Contain Element    xpath=//i[@class="fa fa-question-circle fa-3x"]

Suite with errored tests should display values
    When I go to suite page    &{P4C3S1}
    Then I check suite displayed values    &{P4C3S1}

Suite with skipped tests should display values
    When I go to suite page    &{P2C3S1}
    Then I check suite displayed values    &{P2C3S1}

Suite with disabled tests should display values
    When I go to suite page    &{P5C4S1}
    Then I check suite displayed values    &{P5C4S1}

Suite with only 50% disabled tests should be successfull at 100%
    When I go to suite page    &{P5C4S1}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50
