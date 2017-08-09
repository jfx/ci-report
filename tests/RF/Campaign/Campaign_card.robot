*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/campaign.txt

*** Test Cases ***
Campaign values should be displayed
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then Element Should Contain    css=p.card-text    \#${P1C4.id}
    And I check campaign displayed values    &{P1C4}
    [Teardown]    Teardown

Campaign with more or equal than 95% should be successfull
    [Setup]    Setup
    When I go to campaign page    &{P2C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    95 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-check-circle fa-3x"]
    [Teardown]    Teardown

Campaign between 80% and 95% should be with a warning status
    [Setup]    Setup
    When I go to campaign page    &{P3C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-warning"]    80 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-exclamation-circle fa-3x"]
    [Teardown]    Teardown

Campaign under 80% should be with failed
    [Setup]    Setup
    When I go to campaign page    &{P4C3}
    Then Element Should Contain    xpath=//h4[@class="card-title text-danger"]    79 %
    And Page Should Contain Element    xpath=//i[@class="fa fa-times-circle fa-3x"]
    [Teardown]    Teardown

Campaign with errored tests should display values
    [Setup]    Setup
    When I go to campaign page    &{P4C3}
    Then I check campaign displayed values    &{P4C3}
    [Teardown]    Teardown

Campaign with skipped tests should display values
    [Setup]    Setup
    When I go to campaign page    &{P2C3}
    Then I check campaign displayed values    &{P2C3}
    [Teardown]    Teardown

Campaign with disabled tests should display values
    [Setup]    Setup
    When I go to campaign page    &{P5C4}
    Then I check campaign displayed values    &{P5C4}
    [Teardown]    Teardown

Campaign with only 50% disabled tests should be successfull at 100%
    [Setup]    Setup
    When I go to campaign page    &{P5C4}
    Then Element Should Contain    xpath=//h4[@class="card-title text-success"]    100 %
    And Element Should Contain    p-passed    Passed tests: 50
    And Element Should Contain    p-disabled    Disabled tests: 50
    [Teardown]    Teardown
