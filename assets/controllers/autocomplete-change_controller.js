import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  connect() {
    console.log('auto change connected!')
    document.addEventListener("autocomplete.change", this.autocomplete.bind(this))
  }

  autocomplete(event) {
    console.log(event);
  }
}
