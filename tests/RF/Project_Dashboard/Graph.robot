*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
X-axis should display campaigns list
    Given I go to project dahsboard    ${P1.prefid}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_x_axis
    Then Length Should Be    ${list}    4
    And Should Be Equal As Strings    ${list[0]}    "#${P1C1.crefid}"
    And Should Be Equal As Strings    ${list[3]}    "#${P1C4.crefid}"

Red line should display errored and failed tests for campaigns
    Given I go to project dahsboard    ${P1.prefid}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_red
    Then Length Should Be    ${list}    4
    ${error_c1} =    Evaluate    ${P1C1.errored}+${P1C1.failed}
    And Should Be Equal As Strings    ${list[0]}    ${error_c1}
    ${error_c2} =    Evaluate    ${P1C2.errored}+${P1C2.failed}
    And Should Be Equal As Strings    ${list[1]}    ${error_c2}
    ${error_c3} =    Evaluate    ${P1C3.errored}+${P1C3.failed}
    And Should Be Equal As Strings    ${list[2]}    ${error_c3}

Yellow line should display skipped tests for campaigns
    Given I go to project dahsboard    ${P1.prefid}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_yellow
    Then Length Should Be    ${list}    4
    ${warn_c1} =    Evaluate    ${P1C1.errored}+${P1C1.failed}+${P1C1.skipped}
    And Should Be Equal As Strings    ${list[0]}    ${warn_c1}
    ${warn_c2} =    Evaluate    ${P1C2.errored}+${P1C2.failed}+${P1C2.skipped}
    And Should Be Equal As Strings    ${list[1]}    ${warn_c2}
    And When I go to project dahsboard    ${P2C3.prefid}
    ${content} =    And Get Source
    @{list}    And I get list from js array    ${content}    graph_yellow
    ${warn_c3} =    And Evaluate    ${P2C3.errored}+${P2C3.failed}+${P2C3.skipped}
    Then Should Be Equal As Strings    ${list[2]}    ${warn_c3}

Green line should display skipped tests for campaigns
    Given I go to project dahsboard    ${P1.prefid}
    ${content} =    And Get Source
    @{list}    When I get list from js array    ${content}    graph_green
    Then Length Should Be    ${list}    4
    ${pass_c1} =    Evaluate    ${P1C1.errored}+${P1C1.failed}+${P1C1.skipped}+${P1C1.passed}
    And Should Be Equal As Strings    ${list[0]}    ${pass_c1}
    ${pass_c2} =    Evaluate    ${P1C2.errored}+${P1C2.failed}+${P1C2.skipped}+${P1C2.passed}
    And Should Be Equal As Strings    ${list[1]}    ${pass_c2}
    And When I go to project dahsboard    ${P2C3.prefid}
    ${content} =    And Get Source
    @{list}    And I get list from js array    ${content}    graph_green
    ${pass_c3} =    And Evaluate    ${P2C3.errored}+${P2C3.failed}+${P2C3.skipped}+${P2C3.passed}
    Then Should Be Equal As Strings    ${list[2]}    ${pass_c3}
