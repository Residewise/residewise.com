import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    connect() {
        document.addEventListener('keypress', (event) => {
            if (event.key === "Enter") {
                event.preventDefault()
                this.element.submit()
            }
        })

    }
}
