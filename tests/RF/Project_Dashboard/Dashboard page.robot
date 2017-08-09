*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/dashboard.txt

*** Test Cases ***
Title of window should contain project name
    [Setup]    Setup
    When I go to project dahsboard    ${P1.id}
    Then Title Should Be    ci-report / ${P1.name}
    [Teardown]    Teardown
