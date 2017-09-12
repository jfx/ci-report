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

"GET Campaigns" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1.refid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"GET Projects" request with unknown project returns "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Projects" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1.refid}/campaigns/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
