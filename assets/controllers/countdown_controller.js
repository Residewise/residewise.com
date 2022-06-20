import { Controller } from '@hotwired/stimulus';
import CountDown from 'count-time-down';

export default class extends Controller {

    static values = {
        seconds: Number
    }
    static targets = ['result']

    connect() {
        let display = this.resultTarget
        let seconds = this.secondsValue

        console.log(seconds)

        const cd = new CountDown();
        cd.time = seconds
        cd.cdType = 'h'
        cd.onTick = cd => {
            console.log(cd.time )
            display.innerHTML = cd.hhmmss
            if(cd.time <= 300000 ){
                display.classList.add('text-danger')
            }
        }
        cd.start();

        console.log(cd)


    }
}
