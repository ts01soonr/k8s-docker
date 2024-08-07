import { bookingData } from '../data/booking-data.js'
export class AdminPanel {
  constructor(page) {
    const { name, email, phone, subject, description } = bookingData
    this.page = page
    this.adminPanel = page.locator('//a[text()="Admin panel"]')
    this.username = page.locator('[data-testid="username"]')
    this.password = page.locator('[data-testid="password"]')
    this.submitButton = page.locator('[data-testid="submit"]');
    this.logoutButton = page.locator('//a[text()="Logout"]')
    this.messageButton = page.locator('[href="#/admin/messages"]')
    this.contactName = page.locator('(//p[text()="'+name+'"])[last()]')
    this.message = page.locator('[data-testid="message"]')
  }

  async login() {
    await this.page.goto('https://automationintesting.online/#/admin')
    this.username.type('admin')
    this.password.type('password')
    await this.submitButton.click()
  }

  getLogoutButton() {
    return this.logoutButton.innerText()
  }

  async openMessage() {
    await this.messageButton.click()
    await this.contactName.click()
    this.page.screenshot({ path: 'screenshots/booking-form.png' })
  }

  getMessage() {
    return this.message.innerText()
  }
}