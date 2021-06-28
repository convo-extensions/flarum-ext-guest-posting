import app from 'flarum/forum/app';
import addCreateAccountAlert from './addCreateAccountAlert';
import enableGuestPosting from './enableGuestPosting';
import enableGuestVote from './enableGuestVote';
import modifySignUpModal from './modifySignUpModal';

app.initializers.add('guest-posting', () => {
    addCreateAccountAlert();
    enableGuestPosting();
    enableGuestVote();
    modifySignUpModal();
});

export * from './utils/textFormatter';
