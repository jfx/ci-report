import os
import requests

def Post_Request_With_File_Upload(warning, success):
#    realPath = os.path.realpath(__file__)
#    currentDir = os.path.dirname(realPath)
#    junitFilePath = currentDir + '/../../files/junit-ok1.xml'
    junit_files = [
        ('junitFiles', ('junit-ok1.xml', open('/home/fxs/Src/ci-report/tests/files/junit-ok1.xml', 'rb'), 'application/xml')),
        ('junitFiles', ('junit-ok1.xml', open('/home/fxs/Src/ci-report/tests/files/junit-ok2.xml', 'rb'), 'application/xml')),
    ]
    return requests.post('http://localhost:8000/app_dev.php/api/projects/project-one/campaigns/1/suites/junit', files=junit_files)
