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
    app.extensionSettings['alter-guest-posting'] = () => app.modal.show(new SettingsModal);

    extend(PermissionGrid.prototype, 'startItems', items => {
        allowGuest(items, 'start');
        allowGuest(items, 'startDiscussionsWithoutApproval');
    });

    extend(PermissionGrid.prototype, 'replyItems', items => {
        allowGuest(items, 'reply');
        allowGuest(items, 'replyWithoutApproval');
    });
});
