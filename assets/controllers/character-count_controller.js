import CharacterCounter from 'stimulus-character-counter'

export default class extends CharacterCounter {
  connect () {
    super.connect()
    console.log('Do what you want here.')

    if (this.count >= 600) {
      this.counterTarget.classList.add('text-danger')
    } else {
      this.counterTarget.classList.remove('text-danger')
    }

    this.update()
  }
}
