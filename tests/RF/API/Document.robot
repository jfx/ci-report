*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"POST zip document" request returns HTTP "201" with zip details
    [Tags]    EDIT    STORAGE
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    count    ${zipfile.count}
    And Dictionary Should Contain Item    ${resp.json()}    size    ${zipfile.size}
    And Dictionary Should Contain Item    ${resp.json()}    sha1    ${zipfile.sha1}

"POST zip document" request saves a zip file UID in DB
    [Tags]    EDIT    DB    STORAGE
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${cposition} =    Evaluate    ${P1C4S2.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S2.srefid} - 1
    @{hash} =    Query    select cir_suite.doc_uid from cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.doc_uid IS NOT NULL
    Length Should Be    ${hash[0][0]}    40

"POST zip document" request saves a zip file on campaign id directory
    [Tags]    EDIT    DB    STORAGE    LOCAL
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${cposition} =    Evaluate    ${P1C4S2.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S2.srefid} - 1
    @{hash} =    Query    select cir_suite.doc_uid from cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.doc_uid IS NOT NULL
    File Should Exist    ${STORAGE_DIR}/${P1.id}/${P1C4.id}/${hash[0][0]}

"POST zip document" request with suite with already attached document returns HTTP "400" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()}    code    400
    And Dictionary Should Contain Item    ${resp.json()}    message    A zip file already exists

"POST zip document" request with wrong type file returns HTTP "400" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-err.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    zipfile
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    Please upload a valid zip file

"POST zip document" request without zip file returns HTTP "400" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    And Post Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    zipfile
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    A zip file must be specified.

"POST zip document" request with wrong token returns HTTP "401" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST zip document" request without token returns HTTP "401" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST zip document" request with unknown project refid returns HTTP "404" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/XXX/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST zip document" request with unknown campaign refid returns HTTP "404" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/0/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST zip document" request with unknown suite refid returns HTTP "404" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/0/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST zip document" request with not numeric campaign refid returns HTTP "404" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/X/suites/${P1C4S1.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST zip document" request with not numeric suite refid returns HTTP "404" error
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/X/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
