import {Controller} from '@hotwired/stimulus';
import { easepick } from '@easepick/core';
import { RangePlugin } from '@easepick/range-plugin';

export default class extends Controller {

    static targets = ['picker']

    connect() {
        const picker = new easepick.create({
            element: this.pickerTarget,
            format: "DD.MM.YY",
            css: [
                'https://cdn.jsdelivr.net/npm/@easepick/core@1.2.0/dist/index.css',
                'https://cdn.jsdelivr.net/npm/@easepick/range-plugin@1.2.0/dist/index.css',
            ],
            plugins: [RangePlugin],
            zIndex: 10,
            RangePlugin: {
                tooltip: true,
            },
        });
    }
}
