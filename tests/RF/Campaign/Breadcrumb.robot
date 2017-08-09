*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/campaign.txt
Resource          ../Project_Dashboard/Function/dashboard.txt

*** Test Cases ***
Fields
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Campaign #${P1C4.id}
    [Teardown]    Teardown

Link to home
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When I click on breadcrumb level    1
    Then Location Should Be    ${URL}/
    [Teardown]    Teardown

Link to project
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1C4.pid}
    [Teardown]    Teardown
