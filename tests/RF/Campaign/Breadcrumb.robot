*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Fields
    When I go to campaign page    &{P1C4}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Campaign #${P1C4.crefid}

Link to home
    Given I go to campaign page    &{P1C4}
    When I click on breadcrumb level    1
    Then Location Should Be    ${URL}/

Link to project
    Given I go to campaign page    &{P1C4}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1C4.prefid}
