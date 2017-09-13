*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/suite.txt

*** Test Cases ***
Header should be visible
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain Link    css=a.navbar-brand

Footer should be visible
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain    ci-report v
