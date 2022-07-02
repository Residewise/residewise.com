import { Controller } from '@hotwired/stimulus';

export default class extends Controller {

    static values = {
        user: String
    }

    connect() {
    }

    userValueChanged(current, old){
        console.log('PREVIOUS ', old)
        console.log('CURRENT', current)
    }


}
