import { Controller } from '@hotwired/stimulus';
import {getTimezone, getTimezonesForCountry} from "countries-and-timezones";

const ct = require('countries-and-timezones');
export default class extends Controller {

    static targets = ['timezone', 'region']

    connect() {
        let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
        console.log(timezone)
        let tz = getTimezone(timezone)
        this.regionTarget.value = tz.countries[0]
        this.timezoneTarget.value = tz.name

        this.regionTarget.addEventListener('change', (event) => {
            let newTz = getTimezonesForCountry(this.regionTarget.value)
            this.timezoneTarget.value = newTz[0].name
        })

    }


}
