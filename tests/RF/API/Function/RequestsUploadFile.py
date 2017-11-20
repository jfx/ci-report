import os
import requests

def Post_Request_With_File_Upload(url, file_input_def, data=None, headers=None):
    """ Send a POST request
    ``url`` to send the POST request to
    ``file_input_def`` file definition, a dictionary containing form_field, path_file, mime_type. Example :
          &{file} =	Create Dictionary	form_field=junitfile	path_file=/home/jfx/junit.xml
    ``data`` a dictionary of key-value pairs passed
    ``headers`` a dictionary of headers to use with the request
    """
    basename = os.path.basename(file_input_def['path_file'])
    file_def = [
        (file_input_def['form_field'], (basename, open(file_input_def['path_file'], 'rb')))
    ]
    return requests.post(url, files=file_def, headers=headers, data=data)

