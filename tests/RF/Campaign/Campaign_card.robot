*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Campaign values should be displayed
    When I go to campaign page    &{P1C4}
    Then Element Should Contain    css=p.card-text    \#${P1C4.crefid}
    And I check campaign displayed values    &{P1C4}

Campaign with more or equal than 95% should be successfull
    When I go to campaign page    &{P2C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]

Campaign between 80% and 95% should have a warning status
    When I go to campaign page    &{P3C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]

Campaign under 80% should have failed status
    When I go to campaign page    &{P4C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]

Campaign with a suite no test status should have a unknown status
    When I go to campaign page    &{P8C1S1}
    Then Page Should Contain Element    xpath=//h4[@class="card-title text-secondary"]
    And Page Should Contain Element    xpath=//i[@class="fa fa-question-circle fa-3x"]

Campaign with errored tests should display values
    When I go to campaign page    &{P4C3}
    Then I check campaign displayed values    &{P4C3}

Campaign with skipped tests should display values
    When I go to campaign page    &{P2C3}
    Then I check campaign displayed values    &{P2C3}

Campaign with disabled tests should display values
    When I go to campaign page    &{P5C4}
    Then I check campaign displayed values    &{P5C4}

Campaign with only 50% disabled tests should be successfull at 100%
    When I go to campaign page    &{P5C4}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50

Campaign with end date should display it
    When I go to campaign page    &{P1C1}
    Then Element Should Contain    p-end    ${P1C1.end}
    And Element Should Not Contain    p-end    -

Campaign without end date should "-"
    When I go to campaign page    &{P1C3}
    Then Element Should Contain    p-end    -
