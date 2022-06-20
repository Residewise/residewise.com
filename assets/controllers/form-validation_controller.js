import { Controller } from '@hotwired/stimulus'

export default class extends Controller {

  static values = {
    min: Number,
    max: Number
  }

  connect () {

    let value = this.element.getAttribute('value')

    this.element.addEventListener('keyup', () => {

      if (isNaN(value) && value >= this.minValue && value <= this.maxValue) {
        this.element.classList.add('valid')
      } else {
        this.element.classList.add('is-invalid')
      }
    })
  }
}
