*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Campaigns" request on Project One contains its 4 campaigns
    ${resp} =    When Get Request    cir    /projects/${P1.refid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    4
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C1.id}
    And Label item of list should be equal    ${resp.content}    "warning"    ${P1C1.warning}
    And Label item of list should be equal    ${resp.content}    "success"    ${P1C1.success}
    And Label item of list should be equal    ${resp.content}    "passed"    ${P1C1.passed}
    And Label item of list should be equal    ${resp.content}    "failed"    ${P1C1.failed}
    And Label item of list should be equal    ${resp.content}    "errored"    ${P1C1.errored}
    And Label item of list should be equal    ${resp.content}    "skipped"    ${P1C1.skipped}
    And Label item of list should be equal    ${resp.content}    "disabled"    ${P1C1.disabled}
    And Label item of list should be equal    ${resp.content}    "start"    ${P1C1.start_iso}
    And Label item of list should be equal    ${resp.content}    "end"    ${P1C1.end_iso}

"GET Campaigns" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1.refid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"GET Campaigns" request with unknown project returns "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Campaigns" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1.refid}/campaigns/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
