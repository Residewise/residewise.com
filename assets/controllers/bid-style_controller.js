import {Controller} from '@hotwired/stimulus';

export default class extends Controller
{
    connect() {
        this.element.classList.add('text-warning')
        setTimeout(() => {
            this.element.classList.remove('text-warning')
        }, 500)
    }
}
