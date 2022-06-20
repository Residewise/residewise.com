import { Controller } from '@hotwired/stimulus'
import mapboxgl from 'mapbox-gl'

export default class extends Controller {

  static values = {
    features: String,
    path: String
  }

  static targets = ['result']

  connect () {

    console.log('map controller connected')

    let string = JSON.parse(this.featuresValue)
    let properties = JSON.parse(string)

    mapboxgl.accessToken = 'pk.eyJ1Ijoia2V6MiIsImEiOiJja2ZkcHBwbnYxanlwMnFweHRwdGp6MHJ3In0.13N9E-l_nB_vGKlB9O8QTg'
    const map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/kez2/cl46unvmd000f15o0ayowykuo',
      center: [14.4378, 50.0755],
      zoom: 8
    })

    map.addControl(new mapboxgl.FullscreenControl())
    map.addControl(new mapboxgl.NavigationControl())

    map.on('render', (e) => {
      let center = map.getCenter()
    })

    map.on('load', () => {

      map.addSource('points', {
        type: 'geojson',
        data: {
          type: 'FeatureCollection',
          features: [...properties]
        },
        cluster: true,
        clusterMaxZoom: 14,
        clusterRadius: 50
      })

      map.addLayer({
        'id': 'circle',
        'type': 'circle',
        'source': 'points',
        'paint': {
          'circle-radius': {
            'base': 1.75,
            'stops': [
              [12, 2],
              [22, 180]
            ]
          },
          'circle-color': [
            'match',
            ['get', 'fee'],
            'White',
            '#fbb03b',
            'Black',
            '#223b53',
            'Hispanic',
            '#e55e5e',
            'Asian',
            '#3bb2d0',
            /* other */ '#ccc'
          ]
        }
      })


      map.on('click', 'circle', (e) => {
        map.flyTo({
          center: e.features[0].geometry.coordinates
        })

        const coordinates = e.features[0].geometry.coordinates.slice();
        const html = e.features[0].properties.html

        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
          coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360;
        }

        new mapboxgl.Popup()
          .setLngLat(coordinates)
          .setHTML(html)
          .addTo(map);

      })

      map.on('mouseenter', 'circle', () => {
        map.getCanvas().style.cursor = 'pointer'
      })

      map.on('mouseleave', 'circle', () => {
        map.getCanvas().style.cursor = ''
      })

    })
  }

}



