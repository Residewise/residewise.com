import { Controller } from '@hotwired/stimulus';
import axios from 'axios'

export default class extends Controller {

  static values = {
    type: String,
    path: String
  }

  connect() {
    this.element.addEventListener('click', (e)=>{
      axios.post(this.pathValue, {
        type: this.typeValue
      }).then(()=>{
        console.log(this.typeValue)
      })
    })
  }

}
