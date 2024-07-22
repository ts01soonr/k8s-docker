# k6 api and browser demo

An example project used to demonstrate the browser capabilities of k6.

## Pre-requisites

- k6 version 0.50 and above
- default headless
- K6_BROWSER_HEADLESS=false -> lauch browser

## Running the test under linux/mac

```bash
cd tests
K6_BROWSER_ENABLED=true k6 run <script-to-run>
```
## Running the test under windows command
```bash
cd tests
set "K6_BROWSER_HEADLESS=false" && k6 run tests\multiple-scenario.js
```