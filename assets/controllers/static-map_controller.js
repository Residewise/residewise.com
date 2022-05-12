import { Controller } from '@hotwired/stimulus'
import mapboxgl from 'mapbox-gl'

export default class extends Controller {

  static values = {
    features: Object
  }

  static targets = ['result']

  connect () {

    mapboxgl.accessToken = 'pk.eyJ1Ijoia2V6MiIsImEiOiJja2ZkcHBwbnYxanlwMnFweHRwdGp6MHJ3In0.13N9E-l_nB_vGKlB9O8QTg'
    const map = new mapboxgl.Map({
      container: 'map',
      style: 'mapbox://styles/mapbox/streets-v10',
      center: [14.4378, 50.0755],
      zoom: 8
    })

    map.on('load', () => {

      this.featuresValue.features.forEach((feature) => {

        const coordinates = feature.geometry.coordinates
        const el = document.createElement('div');

        const popup = new mapboxgl.Popup()
          .setLngLat(coordinates)
          .setHTML(feature.properties.html)
          .addTo(map)

        new mapboxgl.Marker(el)
          .setLngLat(coordinates)
          .setPopup(popup)
          .addTo(map)

        el.classList.add('residewise-marker')
        el.setAttribute('id', `map-asset-${feature.properties.id}`)

        el.addEventListener('click', () => {
          el.style.backgroundColor = '#6610f2'
        });
      })

      map.addSource('assets', {
        type: 'geojson',
        data: this.featuresValue,
        cluster: true,
        clusterMaxZoom: 14,
        clusterRadius: 50
      })

      map.addLayer({
        id: 'clusters',
        type: 'circle',
        source: 'assets',
        filter: ['has', 'point_count'],
        paint: {
          'circle-color': '#000000',
          'circle-radius': 15
        }
      })

      map.addLayer({
        id: 'cluster-count',
        type: 'symbol',
        source: 'assets',
        filter: ['has', 'point_count'],
        layout: {
          'text-field': '{point_count_abbreviated}',
          'text-font': ['DIN Offc Pro Medium', 'Arial Unicode MS Bold'],
          'text-size': 13,
        },
        paint:{
          'text-color': '#ffffff'
        }
      })

      map.on('click', 'clusters', (e) => {
        const features = map.queryRenderedFeatures(e.point, {
          layers: ['clusters']
        })
        const clusterId = features[0].properties.cluster_id
        map.getSource('assets').getClusterExpansionZoom(
          clusterId,
          (err, zoom) => {
            if (err) return

            map.easeTo({
              center: features[0].geometry.coordinates,
              zoom: zoom
            })
          }
        )
      })

      map.on('click', 'unclustered-point', (e) => {

        console.log(e)

        const coordinates = e.features[0].geometry.coordinates
        const title = e.features[0].properties.title
        const path = e.features[0].properties.path
        const html = e.features[0].properties.html

        while (Math.abs(e.lngLat.lng - coordinates[0]) > 180) {
          coordinates[0] += e.lngLat.lng > coordinates[0] ? 360 : -360
        }


        const popup = new mapboxgl.Popup()
          .setLngLat(coordinates)
          .setHTML(html)
          .addTo(map)

        console.log(e.features[0].layer.paint)

        e.features[0].layer.paint = {
          'circle-color': 'rgba(255,0,0)'
        }
        // map.setPaintProperty(e.features[0].layer.id, 'circle-color', '#7FFF00');

      })


      map.on('mouseenter', 'clusters', () => {
        map.getCanvas().style.cursor = 'default'
      })
      map.on('mouseleave', 'clusters', () => {
        map.getCanvas().style.cursor = ''
      })
    })
  }

}

