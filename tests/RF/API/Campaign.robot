*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Campaigns" request on Project One contains its 4 campaigns
    ${resp} =    When Get Request    cir    /projects/${P1C1.prefid}/campaigns
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    4
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C1.crefid}
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
    ${resp} =    When Get Request    cir    /projects/${P1C1.prefid}/campaigns/${P1C1.crefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C1.crefid}
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
    ${resp} =    When Get Request    cir    /projects/${P1C4.prefid}/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C4.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C4.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C4.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C4.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C4.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C4.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C4.start_iso}

"GET last campaign" request doesn't return end date when not defined
    ${resp} =    When Get Request    cir    /projects/${P1.prefid}/campaigns/last
    Then Should Be Equal As Strings    ${resp.status_code}    200
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

"POST campaigns" request returns HTTP "201" with campaign data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C0.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C0.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C0.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C0.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C0.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C0.start_iso}
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and start="${P1C0.start_sql}" and end="${P1C0.end_sql}"

"POST campaigns" request with default values returns HTTP "201" with campaign data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C0.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C0.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C0.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C0.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C0.disabled}
    And Dictionary Should Contain Key    ${resp.json()}    start
    And Dictionary Should Not Contain Key    ${resp.json()}    end
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request without start datetime sets it to actual datetime
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    @{queryResults} =    Query    select start from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position}
    And Time should be between 1 minute before and now    ${queryResults[0][0]}

"POST campaigns" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"POST campaigns" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST campaigns" request without token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST campaigns" request with invalid start datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=2017-07-01
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"POST campaigns" request with invalid end datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    end=2017-07-01
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"POST campaigns" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/X/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404

"PUT campaigns" request returns HTTP "200" with campaign data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1C1M.prefid}/campaigns/${P1C1M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C1M.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C1M.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C1M.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C1M.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C1M.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C1M.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C1M.start_iso}
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C1M.pid} and position=${P1C1M.position} and passed=${P1C1M.passed} and failed=${P1C1M.failed} and errored=${P1C1M.errored} and skipped=${P1C1M.skipped} and disabled=${P1C1M.disabled} and start="${P1C1M.start_sql}" and end="${P1C1M.end_sql}"

"PUT campaigns" request with default values returns HTTP "200" with campaign data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C4M.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C4.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C4.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C4.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C4.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C4.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C4M.start_iso}
    And Dictionary Should Not Contain Key    ${resp.json()}    end
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C4M.pid} and position=${P1C4M.position} and passed=${P1C4.passed} and failed=${P1C4.failed} and errored=${P1C4.errored} and skipped=${P1C4.skipped} and disabled=${P1C4.disabled} and end is null

"PUT campaigns" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"PUT campaigns" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"PUT campaigns" request without token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"PUT campaigns" request with invalid start datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=2017-07-01
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"PUT campaigns" request with invalid end datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    end=2017-07-01
    ${resp} =    And Put Request    cir    /projects/${P1C4M.prefid}/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"PUT campaigns" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/X/campaigns/${P1C4M.crefid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404

"PUT campaigns" request with unknown campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1.prefid}/campaigns/0    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"PUT campaigns" request with not numeric campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1.prefid}/campaigns/X    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"PUT campaigns" request without campaign refid returns HTTP "405" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=${P1C1M.start_sql}    end=${P1C1M.end_sql}
    ${resp} =    And Put Request    cir    /projects/${P1C1M.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    405

"DELETE campaigns" request removes campaign 4 of Project One
    [Tags]    EDIT    DB
    ${position} =    Evaluate    ${P1C4.crefid} - 1
    Check If Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${position}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${position} and cir_campaign.id = campaign_id
    Check If Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${position} and cir_campaign.id = campaign_id and cir_suite.id = suite_id
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/${P1C4.prefid}/campaigns/${P1C4.crefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    Check If Not Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${position}
    Check If Not Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${position} and cir_campaign.id = campaign_id
    Check If Not Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${position} and cir_campaign.id = campaign_id and cir_suite.id = suite_id

"DELETE campaigns" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=XXX
    ${resp} =    When Delete Request    cir    /projects/${P1C4.prefid}/campaigns/${P1C4.crefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE campaigns" request without token returns HTTP "401" error
    ${resp} =    When Delete Request    cir    /projects/${P1C4.prefid}/campaigns/${P1C4.crefid}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE campaigns" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/X/campaigns/${P1C4.crefid}    headers=${headers}
    Then Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE campaigns" request with unknown campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1.prefid}/campaigns/0    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE campaigns" request with not numeric campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1.prefid}/campaigns/X    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE campaigns" request without campaign refid returns HTTP "405" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1.prefid}/campaigns    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    405
