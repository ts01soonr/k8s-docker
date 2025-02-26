# Hybrid Client -a2

- As Heartbeat client
- As Runtime client

# Docker Build/Push command

-docker build -t ts01soonr/a2 .
-docker push ts01soonr/a2

# Runng docker

- Service Mode as HB client
```
docker run --rm -p 8888:8888 ts01soonr/a2
```
- Runtime client
```
docker run --rm -e CMD="wait 3" ts01soonr/a2
```

