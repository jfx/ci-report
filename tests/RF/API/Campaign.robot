*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Campaigns" request on Project One contains its 4 campaigns
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    4
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C1.crefid}
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
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"GET Campaigns" request with unknown project returns "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Campaigns" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET campaign" request returns campaign data
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/${P1C1.crefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C1.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1C1.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1C1.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C1.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C1.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C1.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C1.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C1.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C1.start_iso}
    And Dictionary Should Contain Item    ${resp.json()}    end    ${P1C1.end_iso}

"GET campaign" request doesn't return end date when not defined
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/${P1C4.crefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    Log    ${resp.json()}
    And Dictionary Should Contain Key    ${resp.json()}    start
    And Dictionary Should Not Contain Key    ${resp.json()}    end

"GET campaign" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/${P1C1.crefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"GET campaign" request with unknown project refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/X/campaigns/1
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET campaign" request with unknown campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/0
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET campaign" request with not numeric campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/X
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET last campaign" request returns data of last campaign
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    200
    Log    ${resp.json()}
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C4.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1C4.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1C4.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C4.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C4.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C4.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C4.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C4.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C4.start_iso}

"GET last campaign" request doesn't return end date when not defined
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    200
    Log    ${resp.json()}
    And Dictionary Should Contain Key    ${resp.json()}    start
    And Dictionary Should Not Contain Key    ${resp.json()}    end

"GET last campaign" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"GET last campaign" request with unknown project refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/X/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
