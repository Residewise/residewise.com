import { Controller } from '@hotwired/stimulus';
import axios from 'axios'

export default class extends Controller {

  static values = {
    path: String
  }

  connect() {
    this.element.addEventListener('click', (e)=>{
      axios.post(this.pathValue)
    })
  }
}
