*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Fields
    When I go to project dahsboard    ${P1.prefid}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Dashboard

Link to home
    Given I go to project dahsboard    ${P1.prefid}
    When I click on breadcrumb level    1
    Then Location Should Be    ${WEB_URL}/

Link to project
    Given I go to project dahsboard    ${P1.prefid}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1.prefid}
