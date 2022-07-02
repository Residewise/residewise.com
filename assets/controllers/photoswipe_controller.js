import { Controller } from '@hotwired/stimulus';
import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipe from 'photoswipe';
import 'photoswipe/style.css';

export default class extends Controller {
  connect() {
    const lightbox = new PhotoSwipeLightbox({
      gallery: this.element,
      children: 'a',
      showHideAnimationType: 'fade',
      pswpModule: PhotoSwipe
    });
    lightbox.init();

  }
}
