import app from 'flarum/app';
import addCreateAccountAlert from './addCreateAccountAlert';
import enableGuestPosting from './enableGuestPosting';
import modifySignUpModal from './modifySignUpModal';

app.initializers.add('guest-posting', () => {
    addCreateAccountAlert();
    enableGuestPosting();
    modifySignUpModal();
});
