*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/home.txt

*** Test Cases ***
Error 403 pages
    When Run Keyword If    '${ENV}' == 'DEV'    Error page should contain and button should go to    ${WEB_URL}/_error/403    Sorry! You don't have access permissions for that on    a_home    ${WEB_URL}${location_home}

Error 404 pages
    When Run Keyword If    '${ENV}' == 'DEV'    Error page should contain and button should go to    ${WEB_URL}/_error/404    We couldn't find what you're looking for on    a_home    ${WEB_URL}${location_home}
    When Run Keyword Unless    '${ENV}' == 'DEV'    Error page should contain and button should go to    ${WEB_URL}/wrong_path    We couldn't find what you're looking for on    a_home    ${WEB_URL}${location_home}

Error 500 pages
    When Run Keyword If    '${ENV}' == 'DEV'    Error page should contain and button should go to    ${WEB_URL}/_error/500    The web server is returning an internal error for    a_reload    ${WEB_URL}/_error/500

Error 503 pages
    When Run Keyword If    '${ENV}' == 'DEV'    Error page should contain and button should go to    ${WEB_URL}/_error/503    The web server is returning an unexpected temporary error for    a_reload    ${WEB_URL}/_error/503
