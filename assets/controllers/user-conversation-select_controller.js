import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

  connect(){
    const autocomplete = document.getElementById("autocomplete")

    events.forEach((eventType) => {
      autocomplete.addEventListener('autocomplete.change', (event) => {
        console.log(`${eventType} event emitted`, event)
      })
    })
  }

}
