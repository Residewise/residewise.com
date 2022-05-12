import { Controller } from '@hotwired/stimulus'

export default class extends Controller {

  static targets = ['input']

  connect () {

    const el = document.createElement('div')
    el.style.display = 'block'

    let stars = ['s1', 's2', 's3', 's4', 's5']

    stars.forEach((star) => {
      let starEl = document.createElement('i')
      starEl.classList.add('review-star-rating')
      starEl.classList.add('mdi')
      starEl.classList.add('mdi-star-outline')
      el.appendChild(starEl)
    })
    this.inputTarget.insertAdjacentElement('beforebegin', el)

    document.querySelector('.review-star-rating').addEventListener('mouseover', (e) => {
      e.target.classList.remove('mdi-star-outline')
      e.target.classList.add('mdi-star')
    })

    document.querySelector('.review-star-rating').addEventListener('mouseout', (e) => {
      e.target.classList.remove('mdi-star')
      e.target.classList.add('mdi-star-outline')
    })
  }
}
