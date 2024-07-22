import {browser} from 'k6/experimental/browser';
import http from 'k6/http'
import {sleep} from 'k6';
import { describe, expect } from 'https://jslib.k6.io/k6chaijs/4.3.4.0/index.js'

import { AdminPanel } from '../pages/admin-panel.js'
import { Homepage } from '../pages/homepage.js'
import { bookingData } from '../data/booking-data.js'
import { testSetup } from './setup.js'

const { name } = bookingData

export function setup() {
  testSetup()
}

export const options = {
  scenarios: {
    booking: {
      executor: 'per-vu-iterations',
      exec: 'booking',
      vus: 1,
      iterations: 1,
      options: {
        browser: {
          type: 'chromium',
        },
      },
    },
    messages: {
      executor: 'per-vu-iterations',
      exec: 'messages',
      vus: 1,
      iterations: 1,
      options: {
        browser: {
          type: 'chromium',
        },
      },
    },
    login: {
      executor: 'per-vu-iterations',
      exec: 'login',
      vus: 1,
      iterations: 1,
      options: {
        browser: {
          type: 'chromium',
        },
      },
    }
  }

}
export async function booking() {

  const page = browser.newPage();
  try {
    const homepage = new Homepage(page)
    await homepage.goto()
    await homepage.submitForm()

    expect(homepage.getVerificationMessage()).to.contain(name)
  } finally {
    page.close()

  }

}

export async function login() {
  const page = browser.newPage();

  try {
    const adminPanel = new AdminPanel(page)
    await adminPanel.login()

    expect(adminPanel.getLogoutButton()).to.equal('Logout')
  } finally {
    page.close()
  }
}

export function messages() {
  const res = http.post('https://automationintesting.online/message/', JSON.stringify(bookingData), {
    headers: { 'Content-Type': 'application/json' },
  });

  expect(res.status).to.equal(201)
  expect(JSON.parse(res.body).name).to.equal(name)
}