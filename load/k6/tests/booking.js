import {browser} from 'k6/experimental/browser';
import {sleep} from 'k6';
import { describe, expect } from 'https://jslib.k6.io/k6chaijs/4.3.4.0/index.js'

import { AdminPanel } from '../pages/admin-panel.js'
import { Homepage } from '../pages/homepage.js'
import { bookingData } from '../data/booking-data.js'
import { testSetup } from './setup.js'

export function setup() {
  testSetup()
}

export const options = {
  scenarios: {
    booking: {
      executor: 'per-vu-iterations',
      vus: 1,
      iterations: 1,
      options: {
        browser: {
          type: 'chromium',
        },
      },
    },
  }
}


export  default async function(){
  const page = browser.newPage();
  const { name, email, contactNumber, subject } = bookingData
  try{
    const homepage = new Homepage(page)
    await homepage.goto()
    await homepage.submitForm()
    const verifyMessage=homepage.getVerificationMessage()
    console.log('verifyMessage:',verifyMessage )
    expect(verifyMessage).to.contain(name)
    //sleep(10);
    const adminPanel = new AdminPanel(page)
    await adminPanel.login()
    //sleep(30);
    await adminPanel.openMessage()
    //sleep(30);
    const actualMessage = adminPanel.getMessage()

    expect(actualMessage).to.contain(name)
    expect(actualMessage).to.contain(email)

    console.log('actualMessage:', actualMessage);
  }finally{
    page.close();
  }
}