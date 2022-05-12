import { startStimulusApp } from '@symfony/stimulus-bridge';
import FormValidationController from "stimulus-form-validation"
import CharacterCounter from 'stimulus-character-counter'
import TextareaAutogrow from 'stimulus-textarea-autogrow'

// Registers Stimulus controllers from controllers.json and in the controllers/ directory
export const app = startStimulusApp(require.context(
    '@symfony/stimulus-bridge/lazy-controller-loader!./controllers',
    true,
    /\.[jt]sx?$/
));

// register any custom, 3rd party controllers here
// app.register('some_controller_name', SomeImportedController);
app.register("validation", FormValidationController)
app.register('character-counter', CharacterCounter)
app.register('textarea-autogrow', TextareaAutogrow)
