import { randomIntBetween } from 'https://jslib.k6.io/k6-utils/1.1.0/index.js';
import { WebSocket } from 'k6/experimental/websockets';
import { setInterval } from 'k6/experimental/timers';
import {sleep} from 'k6';
import { Counter , Gauge, Rate, Trend } from "k6/metrics";

let MemCount = new Trend("memory");
let CPUCount = new Trend("cpu2");
const GaugeContentSize = new Gauge('ContentSize');
const BASE_URL = __ENV.BASE_URL || 'ws://localhost';
export const options = {
    scenarios: {
        // per_vu_scenario: {
        //     executor: "per-vu-iterations", //5*5=25 iterations
        //     vus: 5,
        //     iterations: 5,
        //     startTime: '3s'
        // },
        // shared_scenario: {
        //     executor: "shared-iterations", // (5/5)=1 iteration per vu, totally 5 iterations
        //     vus: 5,
        //     iterations: 5,
        //     startTime: '0s'
        // },
        // constant_scenario: {
        //     executor: "constant-vus",
        //     vus: 5,
        //     duration: "5s",
        //     startTime: '0s'
        // },
        // ramping_vus_scenario: {
        //     executor: "ramping-vus",
        //     startTime: '0s',
        //     stages: [{
        //             target: 5,
        //             duration: "15s"
        //         }
        //     ]
        // },
        // constant_arrival_scenario: {
        //     executor: "constant-arrival-rate",
        //     rate: 5,
        //     duration: '20s',
        //     preAllocatedVUs: 5,
        //     maxVUs: 10,
        // },
        // ramping_arrival_scenario: {
        //     executor: 'ramping-arrival-rate',
        //     startRate: 2,
        //     timeUnit: '1s',
        //     preAllocatedVUs: 2,
        //     maxVUs: 20,
        //     stages: [{
        //             target: 15,
        //             duration: '30s'
        //         },
        //     ],
        // },
        externally_controller_scenario: {
            executor: 'externally-controlled',
            vus: 1,
            maxVUs: 30,
            duration: '3000s',
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

export default function () {
    const ws = new WebSocket(`${BASE_URL}`);
    ws.addEventListener('open', () => {
        //using websocket for measure cpu-memory
        const t = setInterval(() => {
            var p = 'VirtualBoxVM';
            p = 'rollbackservice';
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
                    const value=msg.replace(' OK','');
                    MemCount.add(value);
                    GaugeContentSize.add(value)
                    logger('[Memory GB]: '+value);
                }
            };
            //console.log(msg.includes('%'))


        });

    });

    sleep(1);
    //ws.close();
    //ws.onclose = () => {console.log('WebSocket connection closed!');};
}