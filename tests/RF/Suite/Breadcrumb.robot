*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/suite.txt
Resource          ../Project_Dashboard/Function/dashboard.txt
Resource          ../Campaign/Function/campaign.txt

*** Test Cases ***
Fields
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Campaign #${P1C4S1.cid}    Suite #${P1C4S1.id}
    [Teardown]    Teardown

Link to home
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    1
    Then Location Should Be    ${URL}/
    [Teardown]    Teardown

Link to project
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1C4S1.pid}
    [Teardown]    Teardown

Link to campaign
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    3
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown
