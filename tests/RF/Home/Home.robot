*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/home.txt

*** Test Cases ***
Title
    [Setup]    Setup
    When I go to Dashboard
    Then Title Should Be    ci-report
    [Teardown]    Teardown
