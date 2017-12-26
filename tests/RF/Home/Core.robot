*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/home.txt

*** Test Cases ***
Footer should be visible
    When I go to Home
    Then Page Should Contain    ci-report -
