

## Hands-on Ansible

Install portainer
- portainer.yaml

setup control package
- installHB.yml

start-stop
- startstop.yaml

custom modules   
- local/library/soonrjar.py

playbook
- local/jar.yml
- ansible-playbook jar.yml -e "cmd='run whoami'"

## Screenshots - Results

custom modules for running jar command
![App Screenshot](/k8s/img/jar.png)