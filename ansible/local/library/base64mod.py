#!/usr/bin/python

# Copyright: (c) 2018, Terry Jones <terry.jones@example.org>
# GNU General Public License v3.0+ (see COPYING or https://www.gnu.org/licenses/gpl-3.0.txt)
from __future__ import (absolute_import, division, print_function)
import base64
import telnetlib
__metaclass__ = type

DOCUMENTATION = r'''
---
module: base64mod

short_description: This is my base64mod module

# If this is part of a collection, you need to use semantic versioning,
# i.e. the version is of the form "2.5.0" and not "2.4".
version_added: "1.0.0"

description: This is my longer description explaining my test module.

options:
    name:
        description: This is the message to send to the test module.
        required: true
        type: str
    new:
        description:
            - Control to demo if the result of this module is changed or not.
            - Parameter description can be a list as well.
        required: false
        type: bool
# Specify this value according to your collection
# in format of namespace.collection.doc_fragment_name
extends_documentation_fragment:
    - my_namespace.my_collection.my_doc_fragment_name

author:
    - Your Name (@yourGitHubHandle)
'''

EXAMPLES = r'''
# Pass in a message
- name: Test with a message
  my_namespace.my_collection.base64mod:
    name: hello world

# pass in a message and have changed true
- name: Test with a message and changed output
  my_namespace.my_collection.base64mod:
    name: hello world
    new: true

# fail the module
- name: Test failure of the module
  my_namespace.my_collection.base64mod:
    name: fail me
'''

RETURN = r'''
# These are examples of possible return values, and in general should use other names for return values.
original_message:
    description: The original name param that was passed in.
    type: str
    returned: always
    sample: 'hello world'
message:
    description: The output message that the base64mod module generates.
    type: str
    returned: always
    sample: 'goodbye'
'''

from ansible.module_utils.basic import AnsibleModule

def call(HOST,port,command):
    tn = telnetlib.Telnet()
    tn.open(HOST, port)
    print(tn.read_until(b"\n", 60).decode())
    print("you are in now")
    tn.write(command.encode('ascii') + b"\n")
    result=tn.read_until(b"-[done!]", 3600).decode()
    print(result)
    tn.sock.close()
    return result

def run_module():
    encode_status = False
    decode_status = False
    # define available arguments/parameters a user can pass to the module
    module_args = dict(
        name=dict(type='str', required=True),
        type=dict(type='str', required=True),
        new=dict(type='bool', required=False, default=False)
    )

    # seed the result dict in the object
    # we primarily care about changed and state
    # changed is if this module effectively modified the target
    # state will include any data that you want your module to pass back
    # for consumption, for example, in a subsequent task
    result = dict(
        changed=False,
        original_message='',
        message=''
    )

    # the AnsibleModule object will be our abstraction working with Ansible
    # this includes instantiation, a couple of common attr would be the
    # args/params passed to the execution, as well as if the module
    # supports check mode
    module = AnsibleModule(
        argument_spec=module_args,
        supports_check_mode=True
    )

    # if the user is working with this module in only check mode we do not
    # want to make any changes to the environment, just return the current
    # state with no modifications
    if module.check_mode:
        module.exit_json(**result)

    # manipulate or modify the state as needed (this is going to be the
    # part where your module will do what it needs to do)

    if module.params['type'].lower() == 'encode':
        encode_status = True

    if module.params['type'].lower() == 'decode':
        decode_status = True

    if not encode_status and not decode_status:
        module.fail_json(msg='Bad input -- It should be encode or decode', **result)

    # Change string to base64 and return it
    if encode_status:
        jar = call('localhost','8888','run whoami')
        message = module.params['name'] + jar
        message_bytes = message.encode('ascii')
        base64_bytes_string = str(base64.b64encode(message_bytes))

        result['original_message'] = message
        result['encoded_message'] = base64_bytes_string
        result['message'] = 'Your data is encoded in base64 using python.'

    if decode_status:
        base64_message = module.params['name']
        base64_bytes = base64_message.encode('ascii')
        base64_bytes_string = str(base64.b64decode(base64_bytes))

        result['original_message'] = base64_message
        result['decoded_message'] = base64_bytes_string
        result['message'] = 'Your data is decoded in base64 using python.'


    # use whatever logic you need to determine whether or not this module
    # made any modifications to your target
    if module.params['new']:
        result['changed'] = True

    # during the execution of the module, if there is an exception or a
    # conditional state that effectively causes a failure, run
    # AnsibleModule.fail_json() to pass in the message and the result
    if module.params['name'] == 'fail me':
        module.fail_json(msg='You requested this to fail', **result)

    # in the event of a successful module execution, you will want to
    # simple AnsibleModule.exit_json(), passing the key/value results
    module.exit_json(**result)


def main():
    run_module()


if __name__ == '__main__':
    main()
