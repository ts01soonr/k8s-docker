## Monitoring CPU and Memory pattern during post operation 

Steps:
- 1. open "UnitTestCaseDemo.sln" under Vistual Studio 2022
- 2. run UnitTestCaseDemo project to get swaggerAPI running under https://localhost:7196/swagger/index.html
- 3. `docker-compose up -d influxdb grafana`
- 4. Load http://localhost:3000, and import the `grafana_dashboard.json` config to a new dashboard.
- 5. k6 run monitorAPI.js --out influxdb=http://[influxdb]:8086/k6
- 6. scale up vus via 'k6 scale --vus '


## Control UI and Result

- with progressive scaling
![App Screenshot](/load/k6/screenshots/k6-scale.png)

- example of result
![App Screenshot](/load/k6/screenshots/monitorAPI.png)


