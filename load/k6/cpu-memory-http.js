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
      create_client: {
        executor: 'externally-controlled',
        exec: 'create_client',
        vus: 100,
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
export function create_client() {
  const url = 'http://localhost:5000/api/v2';
  let rnd = randomIntBetween(100000, 2000000);
  let data = { id: 0, name: 'name'+rnd,phoneNo : '0045'+rnd,emailId: 'email'+rnd+'@gmail.com'};
  //console.log(data);
  // Using a JSON string as body
  let res = http.post(url, JSON.stringify(data), {
    headers: { 'Content-Type': 'application/json' },
  });
  var id = res.json().id;
  if(id % 10000 == 0) logger(res.json().id);
  //logger(res.body);
  //console.log(res.status);
  sleep(1);

}

export function mem_cpu() {
  const res = http.get('https://localhost:5001/info');
  var cpu = res.json().cpuUsage.split(" ")[0]
  var mem = res.json().memoryUsageMB.split(" ")[0]
  var info ="cpu_info:"+cpu+",mem_info:"+mem
  logger(`VU ${__VU} received: ${info}`);
  CPUCount.add(cpu)
  MemCount.add(mem)

  
  sleep(1);
}

export function handleSummaryx(data) {
  const customTitle = 'Measure CPU&Memory';
  const reportTitle = `${customTitle} - ${new Date().toLocaleDateString()}`;

  return {
      'CPU_Memory_Report.html': htmlReport(data, { title: reportTitle }),
  };
}