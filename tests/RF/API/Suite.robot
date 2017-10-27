*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Suites" request on Project One campaign 4 contains its 2 suites
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    2
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S1.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S1.name}
    And Label item of list should be equal    ${resp.content}    "warning"    ${P1C4S1.warning}
    And Label item of list should be equal    ${resp.content}    "success"    ${P1C4S1.success}
    And Label item of list should be equal    ${resp.content}    "passed"    ${P1C4S1.passed}
    And Label item of list should be equal    ${resp.content}    "failed"    ${P1C4S1.failed}
    And Label item of list should be equal    ${resp.content}    "errored"    ${P1C4S1.errored}
    And Label item of list should be equal    ${resp.content}    "skipped"    ${P1C4S1.skipped}
    And Label item of list should be equal    ${resp.content}    "disabled"    ${P1C4S1.disabled}
    And Label item of list should be equal    ${resp.content}    "duration"    ${P1C4S1.duration}
    And Label item of list should be equal    ${resp.content}    "datetime"    ${P1C4S1.time_iso}

"GET Suites" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"GET Suites" request with unknown project returns "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Suites" request with unknown campaign for project returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/0/suites
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Suites" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request returns suite data
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C4S1.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P1C4S1.name}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1C4S1.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1C4S1.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C4S1.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C4S1.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C4S1.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C4S1.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C4S1.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    duration    ${P1C4S1.duration}
    And Dictionary Should Contain Item    ${resp.json()}    datetime    ${P1C4S1.time_iso}

"GET suite" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"GET suite" request with unknown project refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with unknown campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/0/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with unknown suite refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/0
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with not numeric campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/X/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with not numeric suite refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/X
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request removes suite 2 of campaign 4 of Project One
    [Tags]    EDIT    DB
    ${cposition} =    Evaluate    ${P1C4S2.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S2.srefid} - 1
    Check If Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${cposition}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    Check If Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${cposition}
    Check If Not Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Not Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}

"DELETE suites" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=XXX
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE suites" request without token returns HTTP "401" error
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE suites" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/X/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with unknown campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/0/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with not numeric campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/X/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with unknown suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/0    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with not numeric suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/X    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request without campaign refid returns HTTP "405" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    405
