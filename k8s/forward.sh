#!/bin/sh
kubectl port-forward --address 0.0.0.0 $1 28080:80
