*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Header should be visible
    When I go to campaign page    &{P1C4}
    Then Page Should Contain Link    css=a.navbar-brand

Footer should be visible
    When I go to campaign page    &{P1C4}
    Then Page Should Contain    ci-report v
