import { Controller } from '@hotwired/stimulus'
import axios from 'axios'

export default class extends Controller {

  static targets = ['result']

  static values = {
    path: String,
    isLoading: false
  }

  async connect () {
    this.isLoadingValue = true

    await axios.get(this.pathValue).then((response) => {
      this.resultTarget.innerHTML = response.data
    }).finally(()=>{
      this.isLoadingValue = false
    })
  }
}
