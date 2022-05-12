import { Controller } from '@hotwired/stimulus'
import axios from 'axios'

export default class extends Controller {

  static values = {
    path: String
  }

  connect () {

    const el = document.createElement('div')

    el.addEventListener('mouseover', (e) => {
      el.style.display = 'block'
    })

    this.element.addEventListener('mousemove', (e) => {

      let x = e.clientX
      let y = e.clientY

      console.log(x, y)

      el.classList.add('custom-popover')
      el.classList.add('shadow-lg')

      el.style.display = 'block'
      el.innerHTML = '<div class="d-flex justify-content-center align-items-center"> <div class="spinner-border" role="status"> <span class="visually-hidden">Loading...</span> </div> </div>'
      el.style.right = x / 2 + 'px'
      el.style.top = y + 'px'

      axios.get(this.pathValue).then((reponse) => {
        el.innerHTML = reponse.data
        this.element.appendChild(el)
      })

    })

    this.element.addEventListener('mouseout', () => {
      el.style.display = 'none'
      el.innerHTML = ''
    })

  }

}
