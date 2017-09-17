*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Header should be visible
    When I go to project dahsboard    ${P1.prefid}
    Then Page Should Contain Link    css=a.navbar-brand

Footer should be visible
    When I go to project dahsboard    ${P1.prefid}
    Then Page Should Contain    ci-report v
