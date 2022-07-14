import './styles/app.scss';
import  'mapbox-gl/src/css/mapbox-gl.css'
import './bootstrap'
import { Tooltip, Carousel, Popover } from 'bootstrap'
import Masonry from 'masonry-layout'

let tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
let tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new Tooltip(tooltipTriggerEl)
})

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new Popover(popoverTriggerEl,{ html : true })
})

let carousel = document.querySelector('.carousel.preview-carousel')
 new Carousel(carousel, {
  interval: false,
  wrap: true,
  ride: false
})

var elem = document.querySelector('.grid');
let masonry = new Masonry(elem, {
  itemSelector: '.grid-item',
});
