
# Install Minikube
curl -LO https://storage.googleapis.com/minikube/releases/latest/minikube-linux-amd64
sudo install minikube-linux-amd64 /usr/local/bin/minikube && rm minikube-linux-amd64

minikube start

# Install kubectl
https://kubernetes.io/docs/tasks/tools/install-kubectl-linux/
snap install kubectl --classic
kubectl version --client

# Install kind (Kubernetes-in-Docker)
https://kind.sigs.k8s.io/docs/user/quick-start/

kind create cluster --config kind-multi-node.yaml 

kind delete clusters --all

kind create cluster --config kind-local.yaml

------
kind: Cluster 
apiVersion: kind.sigs.k8s.io/v1alpha3 
nodes: 
- role: control-plane 
- role: worker
  extraMounts: 
  - hostPath: ./shared-storage
    containerPath: /var/local-path-provisioner
- role: worker
  extraMounts: 
  - hostPath: ./shared-storage
    containerPath: /var/local-path-provisioner
------

kind delete cluster --name

# Running metric-server on Kind Kubernetes

kubectl apply -f https://github.com/kubernetes-sigs/metrics-server/releases/download/v0.5.0/components.yaml
refer: https://gist.github.com/sanketsudake/a089e691286bf2189bfedf295222bd43
kubectl patch deployment metrics-server -n kube-system --patch "$(cat metric-server-patch.yaml)"

# kubectl

kubectl expose deployment kubia --type=LoadBalancer --port 18080 service/kubia exposed 

kubectl describe pod
kubectl get pods
kubectl exec --stdin --tty mongodb -- /bin/bash

//access a pod with multi-contains:
kubectl exec --stdin --tty fortune -c web-server -- /bin/sh

kubectl exec fortune -c html-generator -- /bin/cat /var/htdocs/index.html

kubectl get pods -o wide
# port-forward
kubectl port-forward --address 0.0.0.0 fortune 28080:80

# kubectl info
kubectl cluster-info
kubectl get nodes 
kubectl get nodes -o wide

sudo docker run -p 1234:8080 -d ts01soonr/kubia

  472  kubectl delete svc kubia
  473  kubectl get svc
  474  kubectl get pods

/////////////
docker build -t ts01soonr/hbsys .
docker push ts01soonr/hbsys
docker run -p 28888:8888 ts01soonr/jre8



sudo nano /etc/nginx/sites-enabled/default2
https://github.com/ChristianLempa/videos/blob/main/nginx-reverseproxy/README.md

/Volume

sudo mkdir -m 777 -p /home/vagrant/kind/pvc/shared/mysql
/tmp/shared/mysql-init