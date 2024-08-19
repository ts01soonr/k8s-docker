import http from 'k6/http';
import { sleep } from 'k6';
import { Counter , Gauge, Rate, Trend } from "k6/metrics";
import { htmlReport } from "https://raw.githubusercontent.com/benc-uk/k6-reporter/main/dist/bundle.js";

let MemCount = new Trend("memory");
let CPUCount = new Trend("cpu2");
export const options = {
  scenarios: {
      
      mem_cpu: {
          executor: 'externally-controlled',
          exec: 'mem_cpu',
          vus: 10,
          maxVUs: 10000,
          startTime: '5s',
          duration: '120s',
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
  const customTitle = 'Load Test on Lab6';
  const reportTitle = `${customTitle} - ${new Date().toLocaleDateString()}`;

  return {
      'Lab6_Report.html': htmlReport(data, { title: reportTitle }),
  };
}