*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt

*** Test Cases ***
Header should be visible
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then Page Should Contain Link    css=a.navbar-brand
    [Teardown]    Teardown

Footer should be visible
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then Page Should Contain    ci-report.io v
    [Teardown]    Teardown
