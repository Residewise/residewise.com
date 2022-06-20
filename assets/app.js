import './styles/app.scss';
import  'mapbox-gl/src/css/mapbox-gl.css'
import './bootstrap'
import { Tooltip, Carousel, Popover } from 'bootstrap'
import Masonry from 'masonry-layout'
import Lightpick from 'lightpick'

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

document.addEventListener('DOMContentLoaded', () => {
  let date = new Date()
  var picker = new Lightpick({
    field: document.querySelector('.pl-datepicker'),
    secondField: document.querySelector('.pl-datepicker-second-input'),
    singleDate: false,
    minDate: new Date(),
    onSelect: function (start, end) {
      var str = '';
      str += start ? start.format('Do MMMM YYYY') + ' to ' : '';
      str += end ? end.format('Do MMMM YYYY') : '...';
      document.getElementById('result-3').innerHTML = str;
    }
  });
})
