import { Controller } from '@hotwired/stimulus'
import mapboxgl from 'mapbox-gl'

export default class extends Controller {

  static targets = ['result', 'latitude', 'longitude']

  connect () {

    mapboxgl.accessToken = 'pk.eyJ1Ijoia2V6MiIsImEiOiJja2ZkcHBwbnYxanlwMnFweHRwdGp6MHJ3In0.13N9E-l_nB_vGKlB9O8QTg'

    const map = new mapboxgl.Map({
      container: this.resultTarget,
      style: 'mapbox://styles/mapbox/streets-v11',
      zoom: 6,
      center: [14.4378, 50.0755]
    })

    map.on('load', () => {
      const marker = new mapboxgl.Marker({
        color: '#000000',
        draggable: true
      })
        .setLngLat([14.4378, 50.0755])
        .addTo(map)

      marker.on('dragend', ()=>{
        const lngLat = marker.getLngLat()
        this.latitudeTarget.setAttribute('value', lngLat.lat)
        this.longitudeTarget.setAttribute('value', lngLat.lng)
      })
    })

  }

}
