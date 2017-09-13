*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/home.txt

*** Test Cases ***
Title
    When I go to Dashboard
    Then Title Should Be    ci-report
