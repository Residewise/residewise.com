import {Controller} from '@hotwired/stimulus'
import axios from 'axios'
import {easingEffects} from "chart.js/helpers";

export default class extends Controller
{
    static targets = ['textInput', 'autocompleteResult', 'input']

    static values = {
        url: String,
        selectedOptions: Array,
    }

    connect() {
        this.textInputTarget.addEventListener('keyup', (e) => {
            axios.get(this.urlValue, {
                params: {
                    q: this.textInputTarget.value,
                    i: JSON.stringify(this.selectedOptionsValue)
                }
            }).then((response) => {
                this.autocompleteResultTarget.innerHTML = response.data
            }).then(() => {
                this.element.querySelectorAll('[role="option"]')
                    .forEach(option => {
                        let id = option.getAttribute('data-autocomplete-select-id-param')
                        if (this.selectedOptionsValue.includes(id)) {
                            option.classList.remove('mdi-checkbox-blank-circle-outline')
                            option.classList.add('mdi-check-circle')
                        }
                    })
            })
        })
    }

    toggle(event) {
        if (this.selectedOptionsValue.includes(event.params.id)) {
            this.selectedOptionsValue = this.selectedOptionsValue.filter(id => id !== event.params.id)
            event.currentTarget.classList.remove('mdi-check-circle')
            event.currentTarget.classList.add('mdi-checkbox-blank-circle-outline')

        } else {
            this.selectedOptionsValue = [...this.selectedOptionsValue, event.params.id]
            event.currentTarget.classList.remove('mdi-checkbox-blank-circle-outline')
            event.currentTarget.classList.add('mdi-check-circle')
        }
        console.log(this.selectedOptionsValue)
    }

}
