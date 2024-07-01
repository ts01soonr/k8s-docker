#!/usr/bin/python

# Copyright: (c) 2024, Fang <fzymon@hotmail.com>
# GNU General Public License v3.0+ (see COPYING or https://www.gnu.org/licenses/gpl-3.0.txt)
from __future__ import (absolute_import, division, print_function)
import telnetlib
__metaclass__ = type

DOCUMENTATION = r'''
---
module: soonrjar

short_description: This is my soonr-jar module

# If this is part of a collection, you need to use semantic versioning,
# i.e. the version is of the form "2.5.0" and not "2.4".
version_added: "1.0.0"

description: This is my longer description explaining my test module.

options:
    host|port|command:
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
    - Fang (@ts01soonr) GitHub
'''

EXAMPLES = r'''
# Pass in a message
- name: Test with a message
  my_namespace.my_collection.soonrjar:
    host: localhost
    port: 8888
    command: run whoami

# pass in a message and have changed true
- name: Test with a message and changed output
  my_namespace.my_collection.soonrjar:
    host: localhost
    port: 8888
    command: run whoami
    new: true

# fail the module
- name: Test failure of the module
  my_namespace.my_collection.soonrjar:
    command: fail me
'''

RETURN = r'''
# These are examples of possible return values, and in general should use other names for return values.
command:
    description: The original command param that was passed in.
    command: str
    returned: always
    command: 'run whomai'
message:
    description: The output message that the soonrjar module generates.
    message: str
    returned: always
    new: 'true'
'''

from ansible.module_utils.basic import AnsibleModule

def has_done(string):
    if any(sub in string for sub in ["[cli]", "[SSH]"]):
        return True
    if any(string.startswith(prefix) for prefix in [
        "help", "az ", "aws ", "run ", "find ", "play ", "env ", "job ", "grpc ", "reads ", "writes ", 
        "sql", "vim", "vm", "ghost", "ex ", "exinfo ", "in ", "info ", "sinfo ", "es ", "ps ", 
        "yara ", "fws ", "rfs ", "drs ", "hbc ", "clist ", "rcall ", "jcall ", "cat ", "repeat ", 
        "do ", "w ", "web ", "pytest", "changes", "qapi"
    ]):
        return True
    if any(string.endswith(suffix) for suffix in [".py", ".ps1"]):
        return True
    return False

def call(HOST,port,command):
    tn = telnetlib.Telnet()
    tn.open(HOST, int(port))
    print(tn.read_until(b"\n", 60).decode())
    print("you are in now")
    tn.write(command.encode('ascii') + b"\nbye\n")
    result=tn.read_all().decode()
    print(result)
    tn.sock.close()
    return result

def run_module():
    # define available arguments/parameters a user can pass to the module
    module_args = dict(
        host=dict(type='str', required=True),
        port=dict(type='str', required=True),
        command=dict(type='str', required=True),
        new=dict(type='bool', required=False, default=False)
    )

    # seed the result dict in the object
    # we primarily care about changed and state
    # changed is if this module effectively modified the target
    # state will include any data that you want your module to pass back
    # for consumption, for example, in a subsequent task
    result = dict(
        changed=False,
        command='',
	output='',
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


    # run soonr.jar command and return it
    command = module.params['command'].strip()
    if bool(command):
        host = module.params['host']
        port = module.params['port']
        #command = module.params['command']
        jar = call(host,port,command)

        result['command'] = command
        result['output'] = jar
        result['message'] = 'Your command is executed by '+host+':'+port



    # use whatever logic you need to determine whether or not this module
    # made any modifications to your target
    if module.params['new']:
        result['changed'] = True

    # during the execution of the module, if there is an exception or a
    # conditional state that effectively causes a failure, run
    # AnsibleModule.fail_json() to pass in the message and the result
    if module.params['command'] == 'fail me':
        module.fail_json(msg='You requested this to fail', **result)

    # in the event of a successful module execution, you will want to
    # simple AnsibleModule.exit_json(), passing the key/value results
    module.exit_json(**result)


def main():
    run_module()


if __name__ == '__main__':
    main()
