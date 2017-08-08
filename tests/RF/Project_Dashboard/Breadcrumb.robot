*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt

*** Test Cases ***
Fields
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then the breadcrumb should contain    ci-report    ${P1.name}    Dashboard
    [Teardown]    Teardown

Link to home
    [Setup]    Setup
    Given I go to project dahsboard    ${P1.id}
    When Click Link    id=br_lvl1
    Then Location Should Be    ${URL}/
    [Teardown]    Teardown

Link to project
    [Setup]    Setup
    Given I go to project dahsboard    ${P1.id}
    When Click Link    id=br_lvl2
    Then Location Should Be    ${URL}${location_dashboard}/${P1.id}
    [Teardown]    Teardown
