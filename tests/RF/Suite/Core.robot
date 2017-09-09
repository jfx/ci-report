*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/suite.txt

*** Test Cases ***
Header should be visible
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain Link    css=a.navbar-brand
    [Teardown]    Teardown

Footer should be visible
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain    ci-report v
    [Teardown]    Teardown
