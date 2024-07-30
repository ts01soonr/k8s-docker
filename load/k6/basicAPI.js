
import http from "k6/http";
import { bookingData } from './data/booking-data.js'

export default function () {
    
    //play-with http.get,post,del
    const login = {
        "username": "admin",
        "password": "password"
    }
    const { name } = bookingData
    //get
    const res = http.get('https://automationintesting.online/message/')
    const list = res.json().messages
    list.forEach(function(message) {
        console.log(JSON.stringify(message)+message.id+"-"+message.name);
    });
    const filteredQuery = res.json().messages.filter(message => message.name == name)

    if (filteredQuery.length < 0) return
    //post - login
    //http.post('https://automationintesting.online/auth/login', JSON.stringify(login), { headers: { 'Content-Type': 'application/json' },});
    //http.post('https://automationintesting.online/auth/login', JSON.stringify(login));
    //del - delete operation
    for (let i = 0; i < filteredQuery.length; i++) {
        const res = http.del(`https://automationintesting.online/message/${filteredQuery[i].id}`, null)
        console.log(res.status)
    
        if(res.status == 202) {
          console.log(`https://automationintesting.online/message/${filteredQuery[i].id} deleted`)
        }
      }
}
