import http from 'k6/http';
import { WebSocket } from 'k6/experimental/websockets';
import { setInterval } from 'k6/experimental/timers';
import {sleep} from 'k6';
import { randomIntBetween } from 'https://jslib.k6.io/k6-utils/1.1.0/index.js';
import { Counter , Gauge, Rate, Trend } from "k6/metrics";
let MemCount = new Trend("memory");
let CPUCount = new Trend("cpu2");
const BASE_URL = __ENV.BASE_URL || 'ws://localhost:8080';

const url = 'https://localhost:5001/api/v2';
//url = 'http://localhost:5196/api/v2';
//url = 'https://localhost:5001/api/v2';
export const options = {
    scenarios: {
        
        create_client: {
            executor: 'externally-controlled',
            exec: 'create_client',
            vus: 10,
            maxVUs: 10000,
            duration: '6000s',
        },
        
        mem_cpu: {
            executor: 'externally-controlled',
            exec: 'mem_cpu',
            vus: 1,
            maxVUs: 1,
            duration: '6000s',
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
function converMemory (data) {
    //
    let number = Number(data);
    if(number<0) return (4+number).toFixed(3);
    else return number;
}

export function create_client() {
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

    const ws = new WebSocket(`${BASE_URL}`);
    ws.addEventListener('open', () => {
        //using websocket for measure cpu-memory
        const t = setInterval(() => {
            var p = 'VirtualBoxVM';
            p = 'SwaggerDemo';
            ws.send('!cpu2 '+p);
            sleep(1);
            ws.send('!mem2 '+p);
            sleep(5);
        }, randomIntBetween(10, 20));
        
        ws.addEventListener('message', (e) => {
            const msg = e.data;
            console.log(dateFormat (new Date (), "%Y-%m-%d %H:%M:%S", true)+ " "+`VU ${__VU} received: ${msg}`);
            if(msg.includes(' OK')) {
                if(msg.includes('%')){
                    const myArray = msg.split(" ");
                    const cpu=myArray[0].replace('%','');
                    CPUCount.add(cpu);                    
                    logger('[CPU%]: '+cpu);
                }
                else{
                    const value=converMemory(msg.replace(' OK',''));
                    MemCount.add(value);
                    logger('[Memory GB]: '+value);
                }
            };
            //console.log(msg.includes('%'))


        });

    });

    sleep(10);
}