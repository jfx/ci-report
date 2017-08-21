*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt

*** Test Cases ***
Fields
    [Setup]    Setup
    When I go to project dahsboard    ${P1.refid}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Dashboard
    [Teardown]    Teardown

Link to home
    [Setup]    Setup
    Given I go to project dahsboard    ${P1.refid}
    When I click on breadcrumb level    1
    Then Location Should Be    ${URL}/
    [Teardown]    Teardown

Link to project
    [Setup]    Setup
    Given I go to project dahsboard    ${P1.refid}
    When I click on breadcrumb level    2
    Then I should be on project dashboard    ${P1.refid}
    [Teardown]    Teardown
