import {extend} from 'flarum/extend';
import app from 'flarum/app';
import SettingsModal from './modals/SettingsModal';
import PermissionGrid from 'flarum/components/PermissionGrid';

function allowGuest(items, key) {
    if (!items.has(key)) {
        return;
    }

    items.get(key).allowGuest = true;
}

app.initializers.add('guest-posting', () => {
    app.extensionSettings['kilowhat-guest-posting'] = () => app.modal.show(SettingsModal);

    extend(PermissionGrid.prototype, 'startItems', items => {
        allowGuest(items, 'start');
        allowGuest(items, 'startDiscussionsWithoutApproval');

        // On beta 15 key is always permission name
        allowGuest(items, 'discussion.startWithoutApproval');
    });

    extend(PermissionGrid.prototype, 'replyItems', items => {
        allowGuest(items, 'reply');
        allowGuest(items, 'replyWithoutApproval');
        allowGuest(items, 'fof-polls-vote');

        // On beta 15 key is always permission name
        allowGuest(items, 'discussion.replyWithoutApproval');
        allowGuest(items, 'votePolls');
        allowGuest(items, 'fof-recaptcha.postWithoutCaptcha');
    });
}, -100);
