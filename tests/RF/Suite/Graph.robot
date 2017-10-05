*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/suite.txt

*** Test Cases ***
Pie chart should display errored and passed tests
    Given I go to suite page    &{P4C3S1}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_data
    Then Length Should Be    ${list}    4
    And Should Be Equal As Strings    ${list[0]}    ${P4C3S1.errored}
    And Should Be Equal As Strings    ${list[1]}    ${P4C3S1.failed}
    And Should Be Equal As Strings    ${list[2]}    ${P4C3S1.skipped}
    And Should Be Equal As Strings    ${list[3]}    ${P4C3S1.passed}

Pie chart should display failed and skipped tests
    Given I go to suite page    &{P2C3S1}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_data
    Then Length Should Be    ${list}    4
    And Should Be Equal As Strings    ${list[0]}    ${P2C3S1.errored}
    And Should Be Equal As Strings    ${list[1]}    ${P2C3S1.failed}
    And Should Be Equal As Strings    ${list[2]}    ${P2C3S1.skipped}
    And Should Be Equal As Strings    ${list[3]}    ${P2C3S1.passed}
