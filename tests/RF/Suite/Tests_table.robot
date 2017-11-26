*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          ../Suite/Function/suite.txt

*** Test Cases ***
Table should have a header
    When I go to suite page    &{P1C4S1}
    Then The table header should contain values    t-test    Package / Class / Name    Status    Duration    Log

Table should list all suites
    When I go to suite page    &{P1C4S1}
    Then The table should contain X rows    t-test    13
    And The table column should contain values    t-test    1    Test 0 in p1c4s1-suite    Test 12 in p1c4s1-suite

The first row should be the first test
    When I go to suite page    &{P1C4S1}
    Then The table row should contain values    t-test    1    Test 0 in p1c4s1-suite    Passed    1s

Check passed value cell
    When I go to suite page    &{P1C4S1}
    Then Element Should Contain    s-test-1    Passed
    And Page Should Contain Element    xpath=//span[@id="s-test-1" and @class="text-success"]

Check failed value cell
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-96    Failed
    And Page Should Contain Element    xpath=//span[@id="s-test-96" and @class="text-danger"]

Check errored value cell
    When I go to suite page    &{P4C2S1}
    Then Element Should Contain    s-test-81    Errored
    And Page Should Contain Element    xpath=//span[@id="s-test-81" and @class="text-danger"]

Check skipped value cell
    When I go to suite page    &{P2C3S1}
    Then Element Should Contain    s-test-100    Skipped
    And Page Should Contain Element    xpath=//span[@id="s-test-100" and @class="text-warning"]

A row test with "Passed" status should have green background color
    When I go to suite page    &{P1C4S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-1" and @class="table-success"]

A row test with "Skipped" status should have yellow background color
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-100" and @class="table-warning"]

A row test with "Failed" should have red background color
    When I go to suite page    &{P2C3S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-96" and @class="table-danger"]

A row test with "Errored" should have red background color
    When I go to suite page    &{P4C2S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-test-81" and @class="table-danger"]

A row test without a system out message should not display a "System Out" button
    When I go to suite page    &{P1C4S1}
    Then Page Should Not Contain Element    b-modal-open-out-1

A row test with a system out message should display a "System Out" button
    When I go to suite page    &{P1C4S2}
    Then Element Should Be Visible    b-modal-open-out-1

"System Out" button should display a modal window with system out log content
    Given I go to suite page    &{P1C4S2}
    When Click Element    b-modal-open-out-1
    And Wait Until Element Is Visible    modal-body-out-1
    Then Element Should Contain    modal-body-out-1    ${P1C4S2T1.out}

"System Out" modal window could be closed by clicking "Close" button
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-out-1
    And Wait Until Element Is Visible    modal-body-out-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    b-modal-close-out-1
    And Wait Until Element Is Not Visible    modal-body-out-1
    Then Element Should Not Be Visible    modal-body-out-1

"System Out" modal window could be closed by clicking on upper right cross
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-out-1
    And Wait Until Element Is Visible    modal-body-out-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    sp-modal-cross-out-1
    And Wait Until Element Is Not Visible    modal-body-out-1
    Then Element Should Not Be Visible    modal-body-out-1

A row test without a system err message should not display a "System Err" button
    When I go to suite page    &{P1C4S1}
    Then Page Should Not Contain Element    b-modal-open-err-1

A row test with a system err message should display a "System Err" button
    When I go to suite page    &{P1C4S2}
    Then Element Should Be Visible    b-modal-open-err-1

"System Err" button should display a modal window with system err log content
    Given I go to suite page    &{P1C4S2}
    When Click Element    b-modal-open-err-1
    And Wait Until Element Is Visible    modal-body-err-1
    Then Element Should Contain    modal-body-err-1    ${P1C4S2T1.err}

"System Err" modal window could be closed by clicking "Close" button
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-err-1
    And Wait Until Element Is Visible    modal-body-err-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    b-modal-close-err-1
    And Wait Until Element Is Not Visible    modal-body-err-1
    Then Element Should Not Be Visible    modal-body-err-1

"System Err" modal window could be closed by clicking on upper right cross
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-err-1
    And Wait Until Element Is Visible    modal-body-err-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    sp-modal-cross-err-1
    And Wait Until Element Is Not Visible    modal-body-err-1
    Then Element Should Not Be Visible    modal-body-err-1

A row test without a failure message should not display a "Failure msg" button
    When I go to suite page    &{P1C4S1}
    Then Page Should Not Contain Element    b-modal-open-fail-1

A row test with a failure message should display a "Failure msg" button
    When I go to suite page    &{P1C4S2}
    Then Element Should Be Visible    b-modal-open-fail-1

"Failure msg" button should display a modal window with failure message content
    Given I go to suite page    &{P1C4S2}
    When Click Element    b-modal-open-fail-1
    And Wait Until Element Is Visible    modal-body-fail-1
    Then Element Should Contain    modal-body-fail-1    ${P1C4S2T1.fail}

"Failure msg" modal window could be closed by clicking "Close" button
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-fail-1
    And Wait Until Element Is Visible    modal-body-fail-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    b-modal-close-fail-1
    And Wait Until Element Is Not Visible    modal-body-fail-1
    Then Element Should Not Be Visible    modal-body-fail-1

"Failure msg" modal window could be closed by clicking on upper right cross
    Given I go to suite page    &{P1C4S2}
    And Click Element    b-modal-open-fail-1
    And Wait Until Element Is Visible    modal-body-fail-1
    Sleep    ${SHORT_SLEEP}
    When Click Element    sp-modal-cross-fail-1
    And Wait Until Element Is Not Visible    modal-body-fail-1
    Then Element Should Not Be Visible    modal-body-fail-1

A suite without test should display "No test"
    When I go to suite page    &{P8C1S1}
    Then Page Should Contain    No test
