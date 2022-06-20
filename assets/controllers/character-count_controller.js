import CharacterCounter from 'stimulus-character-counter'

export default class extends CharacterCounter {
  connect () {
    super.connect()

    this.update()
  }
}
