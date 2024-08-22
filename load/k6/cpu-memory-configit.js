import http from 'k6/http';
import { sleep } from 'k6';
import { Counter , Gauge, Rate, Trend } from "k6/metrics";
import { randomIntBetween } from 'https://jslib.k6.io/k6-utils/1.1.0/index.js';
import { htmlReport } from "https://raw.githubusercontent.com/benc-uk/k6-reporter/main/dist/bundle.js";

let MemCount = new Trend("memory");
let CPUCount = new Trend("cpu2");
let Duration = '300s'
export const options = {
  scenarios: {
      check_package: {
        executor: 'externally-controlled',
        exec: 'check_package',
        vus: 1,
        maxVUs: 10000,
        duration: Duration,
      },
      mem_cpu: {
          executor: 'externally-controlled',
          exec: 'mem_cpu',
          vus: 1,
          maxVUs: 1,
          duration: Duration,
      },
      
  },
};
function dateFormat (date, fstr, utc) {
  utc = utc ? 'getUTC' : 'get';
  return fstr.replace (/%[YmdHMS]/g, function (m) {
    switch (m) {
    case '%Y': return date[utc + 'FullYear'] (); // no leading zeros required
    case '%m': m = 1 + date[utc + 'Month'] (); break;
    case '%d': m = date[utc + 'Date'] (); break;
    case '%H': m = date[utc + 'Hours'] (); break;
    case '%M': m = date[utc + 'Minutes'] (); break;
    case '%S': m = date[utc + 'Seconds'] (); break;
    default: return m.slice (1); // unknown code, remove %
    }
    // add leading zero if required
    return ('0' + m).slice (-2);
  });
}
function logger (data) {
  console.log(dateFormat (new Date (), "%Y-%m-%d %H:%M:%S", true)+ ' '+data);
}
export function check_package() {
  const url = 'http://localhost:5000/A%2C2%3AB%2C3';
  //console.log(data);
  // Using a JSON string as body
  let data = {};
  let res = http.post(url, JSON.stringify(data), {
    headers: { 'Content-Type': 'application/json' },
  });
  //var id = res.json().id;
  //logger("result="+res.json().result);
  //logger(res.body);
  //console.log(res.status);
  sleep(1);

}

export function mem_cpu() {
  const res = http.get('http://localhost:5000/info');
  var cpu = res.json().cpuUsage.split(" ")[0]
  var mem = res.json().memoryUsageMB.split(" ")[0]
  var info ="cpu_info:"+cpu+",mem_info:"+mem
  logger(`VU ${__VU} received: ${info}`);
  CPUCount.add(cpu)
  MemCount.add(mem)

  
  sleep(1);
}

export function handleSummary(data) {
  const customTitle = 'Measure CPU&Memory';
  const reportTitle = `${customTitle} - ${new Date().toLocaleDateString()}`;

  return {
      'CPU_Memory_Report.html': htmlReport(data, { title: reportTitle }),
  };
}