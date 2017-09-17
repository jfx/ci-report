*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/suite.txt

*** Test Cases ***
Fields
    When I go to suite page    &{P1C4S1}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Campaign #${P1C4S1.crefid}    Suite #${P1C4S1.srefid}

Link to home
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    1
    Then Location Should Be    ${URL}/

Link to project
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1C4S1.prefid}

Link to campaign
    When I go to suite page    &{P1C4S1}
    When I click on breadcrumb level    3
    Then I should be on campaign page    &{P1C4}
