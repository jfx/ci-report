*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Y-axis should display campaigns list
    GIven I go to campaign page    &{P1C4}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    bar_y_axis
    Then Length Should Be    ${list}    2
    And Should Be Equal As Strings    ${list[0]}    "#${P1C4S1.srefid}"
    And Should Be Equal As Strings    ${list[1]}    "#${P1C4S2.srefid}"

Red block should display errored and failed tests for campaigns
    GIven I go to campaign page    &{P2C3}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    bar_red
    Then Length Should Be    ${list}    1
    And Should Be Equal As Strings    ${list[0]}    ${P2C3S1.failed}
    GIven I go to campaign page    &{P4C2}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    bar_red
    Then Length Should Be    ${list}    1
    And Should Be Equal As Strings    ${list[0]}    ${P4C2S1.errored}

Yellow block should display skipped tests for campaigns
    GIven I go to campaign page    &{P2C3}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    bar_yellow
    Then Length Should Be    ${list}    1
    And Should Be Equal As Strings    ${list[0]}    ${P2C3S1.skipped}

Green block should display skipped tests for campaigns
    GIven I go to campaign page    &{P1C4}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    bar_green
    Then Length Should Be    ${list}    2
    And Should Be Equal As Strings    ${list[0]}    ${P1C4S1.passed}
    And Should Be Equal As Strings    ${list[1]}    ${P1C4S2.passed}
