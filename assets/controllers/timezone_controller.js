import { Controller } from '@hotwired/stimulus';
import {getTimezone, getTimezonesForCountry} from "countries-and-timezones";

const ct = require('countries-and-timezones');
export default class extends Controller {

    static targets = ['timezone', 'region']

    connect() {
        let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
        let tz = getTimezone(timezone)
        console.log(tz)
        this.regionTarget.value = tz.countries[0]
        this.timezoneTarget.value = tz.name

        this.regionTarget.addEventListener('change', (event) => {
            console.log(newTz)
            this.timezoneTarget.value = newTz[0].name
        })

    }
}
